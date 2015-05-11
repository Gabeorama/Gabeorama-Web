<?php
$configuration = parse_ini_file("configuration.ini");
$host = $configuration["host"];
$user = $configuration["username"];
$pass = $configuration["password"];

$usernameRegex = "/^[a-zA-Z0-9]{1,16}$/";

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

function registerUser($username, $email, $confirmEmail, $password, $confirmPassword) {
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
    if ($email != $confirmEmail) {
        $error .= "Email fields should match.\\n";
    }
    if ($password != $confirmPassword) {
        $error .= "Password fields should match.\\n";
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

if (isset($_POST["submit"])) {
    registerUser($_POST["username"], $_POST["email"], $_POST["confirmEmail"], $_POST["password"], $_POST["confirmPassword"]) or die("invalid input");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Gabeorama.org - Registration</title>
    <script type="text/javascript">
        function validateForm() {
            return (checkEmptyFields() && validateUsername() && validateEmail() && validateConfirmEmail() && validatePassword() && validateConfirmPassword());
        }
        
        function checkEmptyFields() {
            filled = true;
            
            //Check empty fields
            for (var i = 0; i < registration.length; i++) {
                var field = registration[i];
                if (!field.value) {
                    field.style = "background-color: red;";
                    document.getElementById(field.name + "Error").innerHTML = "Fields can't be empty";
                    filled = false;
                } else {
                    field.style = "";
                }
            }
            
            return filled;
        }
            
        function validateEmail() {
            
            //Email 
            if (!/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/.test(registration.email.value)) {
                registration.email.style = "background-color: red;";
                document.getElementById("emailError").innerHTML = "Invalid email address";
                return false;
            } else {
                registration.email.style = "";
                document.getElementById("emailError").innerHTML = "";
                return true;
            }
        }
        
        function validateConfirmEmail() {
            //Check that email fields match
            if (registration.email.value != registration.confirmEmail.value) {
                
                registration.email.style = "background-color: red;";
                registration.confirmEmail.style = "background-color: red;";
                
                document.getElementById("emailError").innerHTML = "Emails must match";
                document.getElementById("confirmEmailError").innerHTML = "Emails must match";
                
                return false;
            } else {
                registration.email.style = "";
                registration.confirmEmail.style = "";
                
                document.getElementById("emailError").innerHTML = "";
                document.getElementById("confirmEmailError").innerHTML = "";
                
                return true;
            }
        }
        
        function validateUsername() {
            //Verify that the name is accepted
            if (!<?php print($usernameRegex); ?>.test(registration.username.value)) {
                registration.username.style = "background-color: red;";
                document.getElementById("usernameError").innerHTML = "Invalid username";
                return false;
            }
            
            //Clear error messages
            registration.username.style = "";
            document.getElementById("usernameError").innerHTML = "";
            return true;
        }
        
        function validatePassword() {
            if (registration.password.value.length < 4) {
                registration.password.style = "background-color: red;";
                document.getElementById("passwordError").innerHTML = "Bad password";
                return false;
            }
        }
            
        function validateConfirmPassword() {
            if (registration.password.value != registration.confirmPassword.value) {
                registration.password.style = "background-color: red;";
                registration.confirmPassword.style = "background-color: red;";
                
                document.getElementById("passwordError").innerHTML = "Passwords must match";
                document.getElementById("confirmPasswordError").innerHTML = "Passwords must match";
            } else {
                registration.password.style = "";
                registration.confirmPassword.style = "";
                
                document.getElementById("passwordError").innerHTML = "";
                document.getElementById("confirmPasswordError").innerHTML = "";
            }
        }
    </script>
</head>
<body>
    <?php if(!isset($_POST["submit"])) { ?>
    <!-- MOCK DESIGN -->
    <table bgcolor="darkgray" align="center" style="text-align: center">
        <tr>
            <td bgcolor="lightgray"><font size="+3">Account registration</font></td>
        </tr>
        <tr>
            <td bgcolor="yellow">Important: <strong>All fields have to be filled in</strong></td>
        </tr>
        <tr>
            <td><div id="errorbox" style="color: red;"></div></td>
        </tr>
        <form name="registration" method="POST" action="register.php" onsubmit="return validateForm()">
            <tr>
                <td>
                    <table style="width: 100%">
                        <tr bgcolor="lightgray">
                            <td>Username:</td>
                            <td><input name="username" type="text" onchange="validateUsername()"/></td>
                            <td><div id="usernameError"></div></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input name="email" type="text" onchange="validateEmail()"/></td>
                            <td><div id="emailError"></div></td>
                        </tr>
                        <tr bgcolor="lightgray">
                            <td>Confirm Email:</td>
                            <td><input name="confirmEmail" type="text" onchange="validateConfirmEmail()"/></td>
                            <td><div id="confirmEmailError"></div></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input name="password" type="password" onchange="validatePassword()"/></td>
                            <td><div id="passwordError"></div></td>
                        </tr>
                        <tr bgcolor="lightgray">
                            <td>Confirm Password:</td>
                            <td><input name="confirmPassword" type="password" onchange="validateConfirmPassword()"/></td>
                            <td><div id="confirmPasswordError"></div></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><input name="submit" type="submit" value="submit" /></td>
            </tr>
        </form>
    </table> <?php } ?>
</body>
</html>