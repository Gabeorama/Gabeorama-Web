<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/surveys.php");
require_once(LIBRARY_PATH . "/pageBuilder.php");

$createForm = array(
    "title" => "Create survey",
    "name" => "createSurvey",
    "action" => "survey/create",
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
        ),
        "element1" => array(
            "type" => "inline",
            "value" => array(
                "typeSelect" => array(
                    "type" => "select",
                    "value" => array("header", "text", "email", "password", "textarea", "dateTime", "panel")
                ),
                "addElement" => array(
                    "type" => "button",
                    "value" => "Add Element"
                )
            )
        )
    )
);

if (!isset($_SESSION["username"])) {
    $createForm["formObjects"]["visibility"]["enabled"] = false;
    $createForm["formObjects"]["visibility"]["selectedValue"] = "private";
}

if (isset($_GET["action"]) && $_GET["action"] == "create") {
    if (isset($_POST["Title"])) {
        //Validate and Add survey
    } else {
        //Build creation form
        buildLayoutWithContent("form_template.php", "Create survey - Gabeorama.org", array("form" => $createForm));
    }
} else {
    buildLayoutWithContent("survey_list.php", "Survey listings - Gabeorama.org");
}

function validate($validationType, $response, $range = array()) {
    switch(strtolower($validationType)) {
        case "email": return filter_var($response, FILTER_VALIDATE_EMAIL);
        case "exists":
        case "notnull": return strlen($response) > 0;
        case "range":
            foreach ($range as $element) {
                if ($response = $element["value"]) return true;
            }
            return false;
        default: return true;
    }
}
?>