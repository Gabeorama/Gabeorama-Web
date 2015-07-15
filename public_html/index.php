<?php
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");

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

function pullPosts() {
    global $configuration;
    
    if ($mysqli = sqlConnect($configuration->db->gabeorama->dbname)) {
        //Fetch newest posts
        if ($result = $mysqli->query("SELECT * FROM `threads` WHERE `Type` = 'NEWS' ORDER BY PublishTime desc LIMIT 10")) {
            //Get results as arrays with identifiers
            $arr = $result->fetch_all(MYSQLI_BOTH);
            //Close streams
            $result->close();
            $mysqli->close();
            return $arr;
        }
    }
}

buildLayoutWithContent("frontpage.php", "Gabeorama.org - Front page");
?>