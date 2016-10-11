<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require(LIBRARY_PATH . "/accounts.php");

$form = array(
	"title" => "Register",
	"name" => "register",
	"action" => "/register/",
	"method" => "POST",
	"submitText" => "Register",
	"formObjects" => array(
	    "login" => array(
	        "value" => "Already have an account? <a href=\"//" . $_SERVER["HTTP_HOST"] . "/login/\">Log in</a>",
            "type" => "info"
        ),
        "star" => array(
            "value" => "* Indicates that a field is required",
            "type" => "info"
        ),
        "accountInfoHeader" => array(
            "value" => "Account info",
            "type" => "header"
        ),
		"username" => array(
		    "text" => "*Username: ",
		    "type" => "text"
		),
		"email" => array(
			"text" => "*Email: ",
			"type" => "text"
		),
		"password" => array(
			"text" => "*Password: ",
			"type" => "password"	
		),
		"confirmPassword" => array(
			"text" => "*Confirm password: ",
			"type" => "password"
		),
        "personaliaHeader" => array(
            "value" => "Personal Info",
            "type" => "header"
        ),
        "fullName" => array(
			"text" => "Full name: ",
			"type" => "text"	
		),
        "phone" => array(
			"text" => "Phone number: ",
			"type" => "text"	
		),
        "address" => array(
			"text" => "Address: ",
			"type" => "text"	
		)
	)
);

if (isset($_POST["submit"]) || isset($_POST["username"])) {
    if ($errors = registerUser($_POST["username"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"], $_POST["fullName"], $_POST["phone"], $_POST["address"])) {
        if ($errors !== true) {
            //Errors
            foreach ($errors as $error) {
                $form["formObjects"][$error[0]]["error"] = $error[1];
            }
            buildLayoutWithContent("form_template.php", "Gabeorama registration", array("form" => $form));
            return;
        }
    	buildLayoutWithContent("contentPage.php", "Registration successful", array(
			"title" => "Registration successful",
			"pageContent" => "You have successfully registered <b>{$_POST["username"]}</b>.<br/>Please wait for your account to be verified by a moderator. This can take up to a few days."
		));
    } else {
    	echo("Something went wrong..");
    }
} else {
	buildLayoutWithContent("form_template.php", "Gabeorama registration", array("form" => $form));
}
