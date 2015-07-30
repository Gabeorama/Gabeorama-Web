<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require(LIBRARY_PATH . "/accounts.php");

$form = array(
	"title" => "Login",
	"name" => "login",
	"action" => "login.php",
	"method" => "POST",
	"submitText" => "Login",
	"formObjects" => array(
		"username" => array(
		    "text" => "Username/E-Mail: ",
		    "type" => "text"
		),
		"password" => array(
			"text" => "Password: ",
			"type" => "password"	
		)
	)
);
//Check if already logged in
if (isset($_SESSION["username"])) {
	buildLayoutWithContent("contentPage.php", "Already logged in", array(
		"title" => "Error, already logged in",
		"pageContent" => "You are already logged in as <b>{$_SESSION["username"]}</b>"
						."You can <a href=\"logout.php\">logout</a> or go back"
	));
//Form submitted
} elseif (isset($_POST["submit"])) {
    //Check login
    if ($user = login($_POST["username"], $_POST["password"])) {
        //Set login in the session
        $_SESSION["username"] = $user["username"];
        $_SESSION["ID"] = $user["ID"];
        
	    //Inform successful logins
	    buildLayoutWithContent("contentPage.php", "Login successful", array(
			"title" => "Login successful",
			"pageContent" => "You are now logged in as <b>{$_SESSION["username"]}</b>"
		));
	} else {
		$form["errorMessage"] = "Invalid username or password";
		buildLayoutWithContent("form_template.php", "Gabeorama - Login", array("form" => $form));
	}
} else {
	//Build login form
    buildLayoutWithContent("form_template.php", "Gabeorama - Login", array("form" => $form));
}
?>