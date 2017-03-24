<?php
require_once(realpath(dirname(__FILE__)) . "/../configuration.php");
require_once(LIBRARY_PATH . "/sqlhelper.php");
$db = $configuration->db->gabeorama;

$usernameRegex = "/^[a-zA-Z0-9]{1,16}$/";

$table_name = "users";

function validateInput($input, $type) {
    global $usernameRegex;
    switch ($type) {
        case "USERNAME":
            // Usernames should contain only alpanumeric characters and be at most 16 characters long
            return preg_match("$usernameRegex", $input);
            break;
        case "PASSWORD":
            // I don't feel like dictating password strengths
            return strlen($input)>3;
        case "EMAIL":
            //Check that it is a valid email address
            return filter_var($input, FILTER_VALIDATE_EMAIL);
    } 
}

function registerUser($username, $email, $password, $confirmPassword, $fullName = "", $phone = "", $address = "") {
    global $db, $table_name;
    $error = [];

    /*Validate input*/
    if (!validateInput($username, "USERNAME")) {
        $error[] = array("username", "'" . htmlspecialchars($username) . "' is not a valid username, please make it maximum 16 characters long and use only alphanumeric characters (A-Z and 0-9).");
    } 
    if (!validateInput($password, "PASSWORD")) {
        $error[] = array("password", "Bad password, use at least 4 characters.");
    }
    if (!validateInput($email, "EMAIL")) {
        $error[] = array("email", "Bad email, please double check.");
    }
    if ($password != $confirmPassword) {
        $error[] = array("confirmPassword", "Password fields should match.");
    }
    if ($phone != "" && !preg_match("/\d+/", $phone)) {
        $error[] = array("phoneNumber", "Phone number is not a number");
    }
    
    //Open database connection
    $mysqli = sqlConnect($db->dbname) or die("sql error");
    createTable($table_name, $mysqli);

    //Search for identical emails and usernames
    $emails = $mysqli->query("SELECT email FROM `$table_name` WHERE `email` = '" . $mysqli->real_escape_string($email) . "'") or die ("sql error");
    $usernames = $mysqli->query("SELECT username FROM `$table_name` WHERE `username` = '" . $mysqli->real_escape_string($username) . "'") or die("sql error");
    
    if ($emails->num_rows > 0) {
        $error[] = array("email", "Email already registered, plese request a new password if you have forgotten it.)");
    }
    
    if ($usernames->num_rows > 0) {
        $error[] = array("username", "Username is already taken, please choose another");
    }
    
    //Close results
    $usernames->close();
    $emails->close();
    
    //Something went wrong
    if ($error != []) {
        return $error;
    }

    /* Validation complete */
    
    //Escape Strings
    $username = $mysqli->real_escape_string($username);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $email = $mysqli->real_escape_string($email);
    $fullName = $mysqli->real_escape_string($fullName);
    $phone = $mysqli->real_escape_string($phone);
    $address = $mysqli->real_escape_string($address);
    
    //Register user in the database
    $registration = $mysqli->query("INSERT INTO `$table_name`(username, email, passwordHash, fullName, phoneNumber, address)"
                                  . "VALUES('$username', '$email', '$password', '$fullName', '$phone', '$address')") or die($mysqli->error);
    $mysqli->close();
    
    //Registration complete
    return true;
}

function login($username, $password) {
    global $db, $table_name;
    
    $mysqli = sqlConnect($db->dbname) or die("SQL error");
    createTable($table_name, $mysqli);

    //No SQL injections please
    $username = $mysqli->real_escape_string($username);
    
    //Check logins with both email and username
    $login = $mysqli->query("SELECT * FROM `$table_name` WHERE username='$username' or email='$username'") or die("SQL error");
    $login = $login->fetch_array();
    
    if (password_verify($password, $login["passwordHash"])) {
        return $login;
    } else {
        return false;
    }
}

function getUser($ID) {
    global $db, $table_name;
    
    $mysqli = sqlConnect($db->dbname) or die("SQL error");
    createTable($table_name, $mysqli);
    //No SQL injections please
    $ID = $mysqli->real_escape_string($ID);
    
    //Check logins with both email and username
    $user = $mysqli->query("SELECT * FROM `$table_name` WHERE ID='$ID'") or die("SQL error");
    return $user->fetch_array();
}

function getUsernameRegex() {
    global $usernameRegex;
    return $usernameRegex;
} ?>