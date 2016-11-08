<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/surveys.php");
require_once(LIBRARY_PATH . "/pageBuilder.php");
require_once(LIBRARY_PATH . "/sqlhelper.php");

$table_survey = "surveys";
$table_questions = "surveyQuestions";
$table_options = "surveyOptions";
$table_responses = "surveyResponses";

$createForm = array(
    "title" => "Create survey",
    "name" => "createSurvey",
    "action" => "survey.php?action=create",
    "method" => "POST",
    "submitText" => "Create",
    "formObjects" => array(
        "warning" => array(
            "value" => "This section is under construction, please report all bugs.",
            "type" => "warning"
        ),
        "header" => array(
            "value" => "Survey Options",
            "type" => "header"
        ),
        "title" => array(
            "text" => "Survey title: ",
            "type" => "text"
        ),
        "description" => array(
            "text" => "Survey description: ",
            "type" => "textarea",
            "help" => "BBCode is supported."
        ),
        "visibility" => array(
            "text" => "Visibility: ",
            "type" => "select",
            "value" => array(
                "public",
                "private"
            )
        ),
        "startTime" => array(
            "text" => "Available from: ",
            "type" => "dateTime"
        ),
        "endTime" => array(
            "text" => "Closes at: ",
            "type" => "dateTime"
        ),
        "header2" => array(
            "value" => "Survey",
            "type" => "header"
        )
    )
);

if (!isset($_SESSION["username"])) {
    $createForm["formObjects"]["visibility"]["enabled"] = false;
    $createForm["formObjects"]["visibility"]["selectedValue"] = "private";
}

if (isset($_GET["action"]) && $_GET["action"] == "create") {
    var_dump($_REQUEST);
    if (isset($_POST["elementCount"])) {
        $numQuestions = intval($_POST["elementCount"]) or die("input error");
        var_dump($numQuestions);

        $mysqli = sqlConnect();

        /** Metadata */
        $title = $mysqli->real_escape_string($_POST["title"]);
        $description = $mysqli->real_escape_string($_POST["description"]);
        $visibility = $mysqli->real_escape_string($_POST["visibility"]);
        $startTime = $mysqli->real_escape_string($_POST["startTime"]);
        $endTime = $mysqli->real_escape_string($_POST["endTime"]);

        //Randomly generated ID
        $R_ID = generateRandomID();

        //User id
        if (isset($_SESSION["ID"])) {
            $userID = $_SESSION["ID"];
        } else {
            $userID = -1;
        }

        $questions = array();

        /** Questions */
        for ($i = 0; $i < $numQuestions; $i++) {
            //Removed row
            if (!isset($_POST["type-" . $i])) {
                continue;
            }
            $text = "";
            if (isset($_POST["text-input-" . $i])) {
                $text = $mysqli->real_escape_string($_POST["text-input-" . $i]);
            }

            switch ($_POST["type-" . $i]) {
                //Panel
                case "0":
                    $panelType = 0;
                    if (isset($_POST["panel-select-" . $i])) {
                        $panelType = $mysqli->real_escape_string($_POST["panel-select-" . $i]);
                    }
                    $questions[] = array(
                        "type" => "panel",
                        "text" => $text,
                        "sortPosition" => $i,
                        "options" => array("panelType" => $panelType)
                    );
                    break;
                //Header
                case "1":
                    $questions[] = array(
                        "type" => "header",
                        "text" => $text,
                        "sortPosition" => $i
                    );
                    break;
                //Text
                case "2":
                    $textType = 0;
                    if (isset($_POST["text-select-" . $i])) {
                        $textType = $mysqli->real_escape_string($_POST["text-select-" . $i]);
                    }
                    $questions[] = array(
                        "type" => "text",
                        "text" => $text,
                        "sortPosition" => $i,
                        "options" => array("textType" => $textType)
                    );
                    break;
                //Selecter
                case "3":
                    $options = array();
                    $inopts = $_POST["option-selecter-" . $i];
                    for ($j =0; $j < sizeof($inopts); $j++) {
                        $options["option-" . $j] = $mysqli->real_escape_string($inopts[$j]);
                    }
                    $questions[] = array(
                        "type" => "select",
                        "text" => $text,
                        "sortPosition" => $i,
                        "options" => $options
                    );
                    break;
                //TextArea
                case "4":
                    $rows = 5;
                    if (isset($_POST["row-input-" . $i])) {
                        $rows = $mysqli->real_escape_string($_POST["row-input-" . $i]);
                    }

                    $questions[] = array(
                        "type" => "textarea",
                        "text" => $text,
                        "sortPosition" => $i,
                        "options" => array("rows" => $rows)
                    );
                    break;
                //Date
                case "5":
                    $questions[] = array(
                        "type" => "datetime",
                        "text" => $text,
                        "sortPosition" => $i,
                        "options" => array(
                            "beforeDate" => $mysqli->real_escape_string($_POST["date-before-" . $i]),
                            "afterDate" => $mysqli->real_escape_string($_POST["date-after-" . $i])
                        )
                    );
            }
        }
        /** TODO Data validation */

        /** Data base update */

        var_dump($questions);

        createTable($table_survey, $mysqli);
        createTable($table_questions, $mysqli);
        createTable($table_options, $mysqli);

        $surveyQuery =  "INSERT INTO `$table_survey` " .
                        "(Random_ID, StartTime, ExpirationTime, Title, Description, Author_ID, visibility) " .
                        "VALUES('$R_ID', '$startTime', '$endTime', '$title', '$description', '$userID', '$visibility') ";

        prepareAndSendQuery($mysqli, $surveyQuery);
        $surveyID = $mysqli->insert_id;

        foreach ($questions as $question) {
            $question_query =   "INSERT INTO `$table_questions` " .
                                "(Survey_ID, QuestionText, QuestionType, SortPosition) " .
                                "VALUES($surveyID, '{$question['text']}', '{$question['type']}', {$question['sortPosition']})";
            prepareAndSendQuery($mysqli, $question_query);
            $question_id = $mysqli->insert_id;

            if (!isset($question["options"])) continue;
            foreach ($question["options"] as $key => $option) {
                $option_query = "INSERT INTO `$table_options` " .
                                "(Question_ID, OptionText, OptionValue) " .
                                "VALUES($question_id, '$key', '$option')";
                prepareAndSendQuery($mysqli, $option_query);
            }
        }
    } else {
        //Build creation form
        buildLayoutWithContent("form_template.php", "Create survey - Gabeorama.org", array("form" => $createForm));
    }
} else {
    buildLayoutWithContent("survey_list.php", "Survey listings - Gabeorama.org");
}

function generateRandomID($length = 16) {
    //a-zA-Z0-9
    $chars = implode(range('a', 'z')) . implode(range('A', 'Z') ). implode(range(0, 9));
    $id = '';

    for ($i = 0; $i < $length; $i++) {
        $random = generateRandomInt(0, strlen($chars));
        $id .= $chars[$random];
    }

    return $id;
}

function generateRandomInt($min = 0, $max) {
    $range = ($max - $min);

    if ($range < 0) return $min;

    $log = log($range, 2);
    $bytes = (int) ($log/8) + 1;
    $bits = (int) $log + 1;
    $filter = (int) (1 << $bits) - 1;

    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter;
    } while ($rnd >= $range);

    return ($min + $rnd);
}

function validate($validationType, $response, $range = array()) {
    switch(strtolower($validationType)) {
        case "email": return filter_var($response, FILTER_VALIDATE_EMAIL);
        case "exists":
        case "notnull": return strlen($response) > 0;
        case "range":
            foreach ($range as $element) {
                if ($response == $element["value"]) return true;
            }
            return false;
        default: return true;
    }
}
?>