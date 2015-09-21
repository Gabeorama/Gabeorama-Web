<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require(LIBRARY_PATH . "/accounts.php");

$form = array(
	"title" => "Register",
	"name" => "register",
	"action" => "register.php" . isset($_GET["source"]) ? "?source=" . $_GET["source"] : "",
	"method" => "POST",
	"submitText" => "Register",
	"formObjects" => array(
        "accountInfoHeader" => array(
            "value" => "<h3>Account info</h3>",
            "type" => "staticText"
        ),
		"username" => array(
		    "text" => "Username: ",
		    "type" => "text"
		),
		"email" => array(
			"text" => "Email: ",
			"type" => "text"
		),
		"confirmEmail" => array(
			"text" => "Confirm email: ",
			"type" => "text"
		),
		"password" => array(
			"text" => "Password: ",
			"type" => "password"	
		),
		"confirmPassword" => array(
			"text" => "Confirm password: ",
			"type" => "password"
		),
        "personaliaHeader" => array(
            "value" => "<h3>Personal Info</h3>\nFill in if you want to.",
            "type" => "staticText"
        ),
        "FullName" => array(
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

if (isset($_POST["submit"])) {
    if (registerUser($_POST["username"], $_POST["email"], $_POST["confirmEmail"], $_POST["password"], $_POST["confirmPassword"]) or die("invalid input")) {
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
?>
