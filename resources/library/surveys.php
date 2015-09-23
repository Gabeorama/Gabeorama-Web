<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/sqlhelper.php");

$surveys_table = "surveys"; //Db for storing general info
$questions_table = "surveyQuestions"; //Db for storing the questions
$options_table = "surveyOptions"; //Db for storing valid answers for choice-type questions
$responses_table = "surveyResponses"; //Db for storing responses


function addSurvey($name, $authorID, $startDate, $endDate, $questions) {
    global $surveys_table, $surveys_database, $questions_table, $options_table, $responses_table, $configuration;

    //Set up connection and verify/create tables
    $mysqli = sqlConnect($configuration->db->gabeorama->dbname);
    createTable($surveys_table, $mysqli, "surveys");
    createTable($questions_table, $mysqli, "surveyQuestions");
    createTable($options_table, $mysqli, "surveyOptions");
    createTable($responses_table, $mysqli, "surveyResponses");

    prepareAndSendQuery($mysqli, "INSERT INTO $surveys_table (startTime, ExpirationTime, title, Author_ID) VALUES ('$startDate', '$endDate', '$name', '$authorID')");
    $surveyID = $mysqli->insert_id;

    foreach ($questions as $question) {
        prepareAndSendQuery($mysqli, "INSERT INTO $questions_table
            (Survey_ID, QuestionText, QuestionType, SortPosition)
            VALUES ('$surveyID', '{$question["text"]}', '{$question["type"]}', '{$question["position"]}')");
        $questionID = $mysqli->insert_id;

        //Add alternatives if necessary
        if ($question["type"] == "radioBox" || $question["type"] == "select" || $question["type"] == "checkBox") {
            foreach ($question["options"] as $option) {
                prepareAndSendQuery($mysqli, "INSERT INTO $options_table
                  (Question_ID, OptionText)
                  VALUES ('$questionID', '$option')");
            }
        }
    }
}