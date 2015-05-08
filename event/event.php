<?php
$configuration = parse_ini_file("../configuration.ini");
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

function getEventsAfter($date, $limit = 25) {
    global $mysqli, $configuration;
    $array;
    
    //Exit on connection error
    if (!sqlConnect($configuration["event_db"])) {
        print("Error connecting to database.\n");
        die("Connection error.\n");
    }
    
    //Fetch events with a time greater to the specified date    
    if ($result = $mysqli->query("SELECT * FROM `events` WHERE 'TIME' > '" . $date . "' "
                                 . "ORDER BY abs(datediff('TIME', '" . $date . "')) "
                                 . "LIMIT " . $limit)) {
        $array = $result->fetch_all();
        
        //Close result
        $result->close();
    } else {
        echo "error";
    }
    
    $mysqli->close();
    return $array;
}

function addEvent($name, $description, $time, $host, $categories) {
    global $mysqli, $configuration;
    
    if (!sqlConnect($configuration["event_db"])) {
        print("Error connecting to database\n");
        die("Connection error.\n");
    }
    
    if (!$mysqli->query("INSERT INTO `event`.`events` (`ID`, `PUBLISH_TIME`, `NAME`, `DESCRIPTION`, `TIME`, `HOST`, `CATEGORIES`) VALUES (NULL, CURRENT_TIMESTAMP, '" . $name . "', '" . $description . "', '" . $time . "', '" . $host . "', '" . $categories . "');")) {
        echo "ERROR\n";
    }
    
    echo "DONE ;)";
    $mysqli->close();
}

?>
