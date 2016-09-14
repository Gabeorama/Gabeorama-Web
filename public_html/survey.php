<?php
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require(LIBRARY_PATH . "/accounts.php");

if (isset($_GET["SurveyID"])) {

}

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