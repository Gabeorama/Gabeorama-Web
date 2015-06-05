<?php
include "accounts.php";

session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Gabeorama.org - Login</title>
</head>
<body>
<?php
if (isset($_SESSION["username"])) {
?>
<!-- Already logged in -->
<h3>Already logged in as <font color="orange"><?php print($_SESSION["username"]); ?></font></h3>
    <?php
//Form submitted
} elseif (isset($_POST["submit"])) {
    //Check login
    if ($user = login($_POST["username"], $_POST["password"])) {
        //Set login in the session
        $_SESSION["username"] = $user["username"];
        
    //Inform successful loginS
    ?>
    <h3>Login completed</h3>
    <p>Welcome, <font color="orange"><?php print($_POST["username"]); ?></font></p>
        <?php
    }
} else {
    ?>
    <!-- MOCK DESIGN -->
    <table bgcolor="darkgray" align="center" style="text-align: center">
        <tr>
            <td bgcolor="lightgray"><font size="+3">Login</font></td>
        </tr>
        <form name="registration" method="POST" action="login.php">
            <tr>
                <td>
                    <table style="width: 100%">
                        <tr bgcolor="lightgray">
                            <td>Username:</td>
                            <td><input name="username" type="text" onchange="validateUsername()"/></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input name="password" type="password" onchange="validatePassword()"/></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><input name="submit" type="submit" value="submit" /></td>
            </tr>
        </form>
    </table>
    <?php
}
?>
</body>
</html>