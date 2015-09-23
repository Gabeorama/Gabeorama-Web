<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/sqlhelper.php");

function pullPosts($start = 0) {
    global $configuration;
    
    if ($mysqli = sqlConnect($configuration->db->gabeorama->dbname)) {
        //Fetch newest posts
        createTable("threads", $mysqli);
        $start = $mysqli->real_escape_string($start);
        if ($result = $mysqli->query("SELECT * FROM `threads` WHERE `Type` = 'NEWS' ORDER BY PublishTime DESC LIMIT 10 OFFSET $start")) {
            //Get results as arrays with identifiers
            $arr = $result->fetch_all(MYSQLI_BOTH);
            //Close streams
            $result->close();
            $mysqli->close();
            return $arr;
        }
    }
}

function createPost($author, $title, $content, $type) {
    global $configuration;
    if ($mysqli = sqlConnect($configuration->db->gabeorama->dbname)) {
        createTable("threads", $mysqli);

        //Escape stuff
        $author = $mysqli->real_escape_string($author);
        $title = $mysqli->real_escape_string($title);
        $content = $mysqli->real_escape_string($content);
        $type = $mysqli->real_escape_string($type);
        
        //Insert
        return $result = $mysqli->query("INSERT INTO `threads` (`ID`, `Type`, `Author`, `Title`, `Content`) VALUES ('test', '$type', '$author', '$title', '$content')") or die($mysqli->error);
    }
}
?>