<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/sqlhelper.php");

$surveys_table = "surveys"; //Db for storing general info
$questions_table = "surveyQuestions"; //Db for storing the questions
$options_table = "surveyOptions"; //Db for storing valid answers for choice-type questions
$responses_table = "surveyResponses"; //Db for storing responses


function addSurvey($name, $authorID, $startDate, $endDate, $questions) {
    global $surveys_table, $questions_table, $options_table, $responses_table, $configuration;

    //Set up connection and verify/create tables
    $mysqli = sqlConnect($configuration->db->gabeorama->dbname);
    createTable($surveys_table, $mysqli, "surveys");
    createTable($questions_table, $mysqli, "surveyQuestions");
    createTable($options_table, $mysqli, "surveyOptions");
    createTable($responses_table, $mysqli, "surveyResponses");

    //Avert injections
    $name = $mysqli->real_escape_string($name);
    $authorID = $mysqli->real_escape_string($authorID);
    $startDate = $mysqli->real_escape_string($startDate);
    $endDate = $mysqli->real_escape_string($endDate);
    $random_id = generateRandomID();

    prepareAndSendQuery($mysqli, "INSERT INTO $surveys_table (Random_ID, startTime, ExpirationTime, title, Author_ID) VALUES ('$random_id', '$startDate', '$endDate', '$name', '$authorID')");
    $surveyID = $mysqli->insert_id;

    foreach ($questions as $question) {

        $qText = $mysqli->real_escape_string($question["text"]);
        $qType = $mysqli->real_escape_string($question["type"]);
        $qPos = $mysqli->real_escape_string($question["position"]);
        $qValidation = isset($question["validation"]) ? $mysqli->real_escape_string($question["validation"]) : null;

        prepareAndSendQuery($mysqli, "INSERT INTO $questions_table
            (Survey_ID, QuestionText, QuestionType, QuestionValidation, SortPosition)
            VALUES ('$surveyID', '$qText', '$qType', '$qValidation', '$qPos')");
        $questionID = $mysqli->insert_id;

        //Add alternatives if necessary
        if (isset($question["options"])) {
            foreach ($question["options"] as $option) {
                $oText = $mysqli->real_escape_string($option["text"]);
                $oValue = $mysqli->real_escape_string($option["value"]);
                prepareAndSendQuery($mysqli, "INSERT INTO $options_table
                  (Question_ID, OptionText, OptionValue)
                  VALUES ('$questionID', '$oText', $oValue)");
            }
        }
    }

    $mysqli->close();
}

function getSurveyByRID($randomID) {
    global $surveys_table;
    $mysqli = sqlConnect();

    $randomID = $mysqli->real_escape_string($randomID);

    $query = $mysqli->prepare("SELECT Survey_ID FROM $surveys_table WHERE Random_ID=?");
    $query->bind_param("s", $randomID);
    $query->execute();
    $query->bind_result($surveyID);
    $query->fetch();
    $query->close();
    $mysqli->close();

    return $surveyID;
}

function getSurveys($limit = 10, $offset = 0) {
    global $surveys_table, $questions_table, $options_table, $responses_table, $configuration;

    $mysqli = sqlConnect($configuration->db->gabeorama->dbname);

    if($result = $mysqli->query("SELECT t.*, count(o.Survey_ID) " .
            "FROM $surveys_table t " .
            "LEFT JOIN $responses_table o on t.Survey_ID = o.SurveyID " .
            "GROUP BY t.* " .
            "WHERE TIME > " . date("Y-m-d H:i:s") . " " .
            "ORDER BY COUNT(o.Survey_ID) DESC " .
            "LIMIT $limit " .
            "OFFSET $offset")) {
        if (method_exists('mysqli_result', 'fetch_all')) {
            $arr = $result->fetch_all(MYSQLI_BOTH);
        } else {
            for ($arr = array(); $tmp = $result->fetch_array(MYSQLI_BOTH);) $arr[] = $tmp;
        }
        return $arr;
    }
    return false;
}

function getSurvey($surveyID) {
    global $surveys_table, $questions_table, $options_table, $responses_table, $configuration;

    //Ignore empty requests
    if ($surveyID == null || strlen($surveyID) == 0) return null;

    $mysqli = sqlConnect();
    $surveyID = $mysqli->real_escape_string($surveyID);

    /* Get main survey info */
    $query = $mysqli->prepare("SELECT StartTime, ExpirationTime, Title, Author_ID FROM $surveys_table WHERE Survey_ID=?");
    $query->bind_param("i", $surveyID);

    $query->execute();

    //Get relevant values
    $query->bind_result($startTime, $endTime, $title, $authorID);
    $query->fetch();

    $survey = array(
        "title" => $title,
        "startTime" => $startTime,
        "endTime" => $endTime,
        "authorID" => $authorID,
        "questions" => array()
    );

    $query->close();

    /* Get questions info */
    $query = $mysqli->prepare(
        "SELECT q.Question_ID, QuestionText, QuestionType, QuestionValidation, OptionText, OptionValue FROM $questions_table q
        LEFT JOIN $options_table o
        ON (q.Question_ID = o.Question_ID)
        WHERE q.Survey_ID=?
        ORDER BY SortPosition ASC") or die($mysqli->error);
    $query->bind_param("i", $surveyID);

    $query->execute();

    //Get relevant values
    $query->bind_result($questionID, $qText, $qType, $qValidation, $oText, $oValue);

    //Fetch all questions and options
    while ($query->fetch()) {

        //Add to array
        if (!isset($survey["questions"][$questionID])) {
            $survey["questions"][$questionID] = array(
                "ID" => $questionID,
                "text" => $qText,
                "type" => $qType,
                "validation" => $qValidation
            );
        }

        //This questions has defined options
        if ($oText != NULL) {
            $survey["questions"][$questionID]["options"][] = array(
                "text" => $oText,
                "value" => $oValue
            );
        }
    }

    $query->close();
    $mysqli->close();

    return $survey;
}

function addResponse($surveyID, $userID, $survey) {
    global $responses_table;
    $mysqli = sqlConnect();

    $surveyID = $mysqli->real_escape_string($surveyID);
    $userID = $mysqli->real_escape_string($userID);

    foreach ($survey as $response) {
        $questionID = $mysqli->real_escape_string($response["ID"]);
        $value = $mysqli->real_escape_string($response["value"]);
        prepareAndSendQuery($mysqli, "INSERT INTO $responses_table (Survey_ID, Respondant_ID, Question_ID, ResponseValue)
            VALUES ('$surveyID', '$userID', '$questionID', '$value')");
    }

    $mysqli->close();
}

function hasResponded($userID, $surveyID) {
    global $responses_table;

    $mysqli = sqlConnect();
    $userID = $mysqli->real_escape_string($userID);
    $surveyID = $mysqli->real_escape_string($surveyID);

    $check = $mysqli->query("SELECT * FROM $responses_table WHERE Respondant_ID=$userID AND Survey_ID=$surveyID");
    $mysqli->close();

    return ($check->num_rows != 0);
}

function generateRandomID($length = 16) {
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $randomized = "";

    while (strlen($randomized) < $length) {
        $randomized .= $characters[rand(0, strlen($characters) -1)];
    }

    return $randomized;
}