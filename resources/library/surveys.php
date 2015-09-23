<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/sqlhelper.php");

$surveys_table = "surveys"; //Db for storing general info
$questions_table = "surveyQuestions"; //Db for storing the questions
$options_table = "surveyOptions"; //Db for storing valid answers for choice-type questions
$responses_table = "surveyResponses"; //Db for storing responses


function addSurvey($name, $startDate, $endDate, $questions) {
    global $surveys_table, $surveys_database, $questions_table, $options_table, $responses_table, $configuration;

    //Set up connection and verify/create tables
    $mysqli = sqlConnect($configuration->db->gabeorama->dbname);
    createTable($surveys_table, $mysqli, "surveys");
    createTable($questions_table, $mysqli, "surveyQuestions");
    createTable($options_table, $mysqli, "surveyOptions");
    createTable($responses_table, $mysqli, "surveyResponses");
}