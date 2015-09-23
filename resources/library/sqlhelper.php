<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));

function sqlConnect($database) {
    global $configuration;
    
    //Open a new connection to specified database
    $db = $configuration->db->gabeorama;
    $mysqli = new mysqli($db->dbhost, $db->dbuser, $db->dbpass, $database);
    
    //Check for errors
    if ($mysqli->connect_errno) {
        echo "ERROR: " . $mysqli->connect_error . "\n";
        return false;
    }
    
    return $mysqli;
}
function createTable($table, $mysqli) {
    $query = "CREATE TABLE IF NOT EXISTS $table ";
    
    switch($table) {
        case "users":
            $query .= "(\n ID int PRIMARY KEY AUTO_INCREMENT,\n"
                . "registerDate dateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                . "username text NOT NULL,\n"
                . "email text NOT NULL,\n"
                . "passwordHash text NOT NULL,\n"
                . "permissionsGroup varchar(32) NOT NULL DEFAULT 'unverified',\n"
                . "fullName text,\n"
                . "phoneNumber int,\n"
                . "address text)";
            break;
        case "threads":
            $query .= "(\n ID int PRIMARY KEY AUTO_INCREMENT,\n"
                . "PublishTime dateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                . "Author text NOT NULL,\n"
                . "Title text NOT NULL,\n"
                . "Content text NOT NULL,\n"
                . "Type text)";
            break;
        default:
            return;
    }
    
    $mysqli->query($query) or die($mysqli->error);
}