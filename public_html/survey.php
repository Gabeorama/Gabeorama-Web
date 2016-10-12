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
            "value" => "Survey options: ",
            "type" => "header"
        ),
        "title" => array(
            "text" => "Survey title: ",
            "type" => "text"
        ),
        "description" => array(
            "text" => "Survey description: ",
            "type" => "textarea"
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
        )
    )
);

if (!isset($_SESSION["username"])) {
    $createForm["formObjects"]["visibility"]["enabled"] = false;
    $createForm["formObjects"]["visibility"]["value"] = "private";
}

/*if (isset($_GET["Survey_ID"])) {
    $surveyID = $_GET["Survey_ID"];
    $realSurveyID = getSurveyByRID($surveyID);
    if ($survey = getSurvey($realSurveyID)) {
        //Prompt to login
        if (!isset($_SESSION["ID"])) {
            $source = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            buildLayoutWithContent("contentPage.php", "Login Required", array(
                "title" => "You have to log in to access this item",
                "pageContent" => "Please <a href=\"login.php?source=$source\">Log in</a>"
                    . "or <a href=\"register.php?source=$source\">register</a>."
            ));
            //Already responded
        } elseif (hasResponded($_SESSION["ID"], $realSurveyID)) {
            buildLayoutWithContent("contentPage.php", "Already Answered", array(
                "title" => "Oy, looks like you've been here already",
                "pageContent" => "Sorry, but we're current only looking for one response per person :)"
            ));
            //Submitted form
        } elseif (isset($_POST["submit"])) {
            $error = "";
            $response = array();
            foreach ($survey["questions"] as $question) {
                if ((!isset($_POST[$question["ID"]]) && !validate($question["validation"], "")) || !validate($question["validation"], $_POST["${question["ID"]}"], isset($question["options"]) ? $question["options"] : array())) $error .= "Input error for field {$question["text"]} (expected type {$question["validation"]})\\n";
                $response[] = array(
                    "ID" => $question["ID"],
                    "value" => isset($_POST[$question["ID"]]) ? $_POST[$question["ID"]] : null
                );
            }

            if ($error != "") {
                ?>
                <script type="text/javascript">
                    //Alert the user of their error and go back to the previous page
                    alert("<?php echo $error; ?>");
                    history.back();
                </script>
                <?php
                //Legacy code for browsers without javascript
                die(preg_replace("/\\\\n/", "<br />", $error));
            }

            addResponse($realSurveyID, $_SESSION["ID"], $response);
            buildLayoutWithContent("contentPage.php", "Answer sent (Survey #$surveyID)", array(
                "title" => "Your response has been recorded, thank you ;)",
                "pageContent" => ""
            ));
        } else {
            $survey["action"] = "survey.php?Survey_ID=$surveyID";
            $survey["method"] = "POST";
            $survey["name"] = "survey_$surveyID";
            buildLayoutWithContent("form_template.php", "Answering Survey: {$survey["title"]} (#$surveyID)", array("form" => $survey));
        }
    }*/
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