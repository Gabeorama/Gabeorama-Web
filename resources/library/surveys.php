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

    prepareAndSendQuery($mysqli, "INSERT INTO $surveys_table (startTime, ExpirationTime, title, Author_ID) VALUES ('$startDate', '$endDate', '$name', '$authorID')");
    $surveyID = $mysqli->insert_id;

    foreach ($questions as $question) {
        $random_id = generateRandomID();
        prepareAndSendQuery($mysqli, "INSERT INTO $questions_table
            (Survey_ID, Random_ID, QuestionText, QuestionType, SortPosition)
            VALUES ('$surveyID', '{generateRandom();}' '{$question["text"]}', '{$question["type"]}', '{$question["position"]}')");
        $questionID = $mysqli->insert_id;

        //Add alternatives if necessary
        if ($question["type"] == "radioBox" || $question["type"] == "select" || $question["type"] == "checkBox") {
            foreach ($question["options"] as $option) {
                prepareAndSendQuery($mysqli, "INSERT INTO $options_table
                  (Question_ID, OptionText, OptionValue)
                  VALUES ('$questionID', '{$option["text"]}', {$option["value"]})");
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

    return $surveyID;
}

function getSurvey($surveyID) {
    global $surveys_table, $questions_table, $options_table, $responses_table, $configuration;

    //Ignore empty requests
    if ($surveyID == null || strlen($surveyID) == 0) return null;

    $mysqli = sqlConnect();

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
        "SELECT q.Question_ID, QuestionText, QuestionType, OptionText, OptionValue FROM $questions_table q
        LEFT JOIN $options_table o
        ON (q.Question_ID = o.Question_ID)
        WHERE q.Survey_ID=?
        ORDER BY SortPosition ASC") or die($mysqli->error);
    $query->bind_param("i", $surveyID);

    $query->execute();

    //Get relevant values
    $query->bind_result($questionID, $qText, $qType, $oText, $oValue);

    //Fetch all questions and options
    while ($query->fetch()) {

        //Add to array
        if (!isset($survey["questions"][$questionID])) {
            $survey["questions"][$questionID] = array(
                "text" => $qText,
                "type" => $qType,
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

    return $survey;
}

function generateRandomID($length = 16) {
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $randomized = "";

    while (strlen($randomized) < $length) {
        $randomized .= $characters[rand(0, strlen($characters) -1)];
    }

    return $randomized;
}