<?php
$configuration = parse_ini_file("configuration.ini");
$host = $configuration["host"];
$user = $configuration["username"];
$pass = $configuration["password"];

function sqlConnect($database) {
    global $host, $user, $pass;
    
    //Open a new connection to specified database
    $mysqli = new mysqli($host, $user, $pass, $database);
    
    //Check for errors
    if ($mysqli->connect_errno) {
        echo "ERROR: " . $mysqli->connect_error . "\n";
        return false;
    }
    
    return $mysqli;
}

function validateInput($input, $type) {
    switch ($type) {
        case "USERNAME":
            // Usernames should contain only alpanumeric characters and be at most 16 characters long
            return preg_match("/^[a-zA-Z0-9]{1,16}$/", $input);
            break;
        case "PASSWORD":
            // I don't feel like dictating password strengths
            return strlen($input)>3;
        case "EMAIL":
            //Check that it is a valid email address
            return filter_var($input, FILTER_VALIDATE_EMAIL);
    } 
}

function registerUser($username, $email, $password) {
    global $configuration;
    $tableName = "users";
    $error = "";
    
    /*Validate input*/
    if (!validateInput($username, "USERNAME")) {
        $error .= "'" . htmlspecialchars($username) . "' is not a valid username, please make it maximum 16 characters long and use only alphanumeric characters (A-Z and 0-9).\\n";
    } 
    if (!validateInput($password, "PASSWORD")) {
        $error .= "Bad password, use at least 4 characters.\\n";
    }
    if (!validateInput($email, "EMAIL")) {
        $error .= "Bad email, please double check.\\n";
    }
    
    //Open database connection
    $mysqli = sqlConnect($configuration["user_db"]) or die("sql error");
    
    //Search for identical emails and usernames
    $emails = $mysqli->query("SELECT email FROM `$tableName` WHERE `email` = '" . $mysqli->real_escape_string($email) . "'") or die ("sql error");
    $usernames = $mysqli->query("SELECT username FROM `$tableName` WHERE `username` = '" . $mysqli->real_escape_string($username) . "'") or die("sql error");
    
    if ($emails->num_rows > 0) {
        $error .= "Email already registered, plese request a new password if you have forgotten it.\\n";
    }
    
    if ($usernames->num_rows > 0) {
        $error .= "Username is already taken, please choose another";
    }
    
    //Close results
    $usernames->close();
    $emails->close();
    
    //Something went wrong
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
    
    /* Validation complete */
    
    //Escape Strings
    $username = $mysqli->real_escape_string($username);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $email = $mysqli->real_escape_string($email);
    
    //Register user in the database
    $registration = $mysqli->query("INSERT INTO `$tableName`(username, email, passwordHash)"
                                  . "VALUES('$username', '$email', '$password')") or die($mysqli->errorno());
    $mysqli->close();
    
    //Registration complete
    print("Registration successfull, thank you.\n");
    return true;
}
?>