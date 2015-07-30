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
?>