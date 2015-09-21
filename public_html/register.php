<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require(LIBRARY_PATH . "/accounts.php");

$form = array(
	"title" => "Register",
	"name" => "register",
	"action" => "register.php",
	"method" => "POST",
	"submitText" => "Register",
	"formObjects" => array(
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
		)
	)
);

if (isset($_POST["submit"])) {
    if (registerUser($_POST["username"], $_POST["email"], $_POST["confirmEmail"], $_POST["password"], $_POST["confirmPassword"]) or die("invalid input")) {
    	buildLayoutWithContent("contentPage.php", "Registration successful", array(
			"title" => "Registration successful",
			"pageContent" => "You have successfully registered <b>{$_POST["username"]}</b>."
		));
    } else {
    	echo("Something went wrong..");
    }
} else {
	buildLayoutWithContent("form_template.php", "Gabeorama registration", array("form" => $form));
}
?>
