<?php
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/surveys.php");
require_once(LIBRARY_PATH . "/pageBuilder.php");

if (isset($_GET["Survey_ID"])) {
    $surveyID = $_GET["Survey_ID"];
    if ($survey = getSurvey(getSurveyByRID($surveyID))) {
        //Submitted form
        if (isset($_POST["submit"])) {
            //TODO Add validation
            //TODO Add result storing
        } else {
            $survey["action"] = "survey.php?Survey_ID=$surveyID";
            $survey["method"] = "POST";
            $survey["name"] = "survey$surveyID";
            buildLayoutWithContent("form_template.php", "Answering Survey: {$survey["title"]} (#$surveyID)", array("form" => $survey));
        }
    }
}