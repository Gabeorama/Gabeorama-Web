<?php
$configuration = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "Gabeorama-Web/configuration.ini");
$host = $configuration["host"];
$user = $configuration["username"];
$pass = $configuration["password"];
$mysqli;

function sqlConnect($database) {
    global $mysqli, $host, $user, $pass;
    
    //Open a new connection to specified database
    $mysqli = new mysqli($host, $user, $pass, $database);
    
    //Check for errors
    if ($mysqli->connect_errno) {
        echo "ERROR: " . $mysqli->connect_error . "\n";
        return false;
    }
    
    return true;
}

//Exit on connection error
if (!sqlConnect($configuration["event_db"])) {
    print("Error connecting to database.\n");
    die("Connection error.\n");
}

//End connection
$mysqli->close();
