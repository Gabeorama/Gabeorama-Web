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

function pullPosts() {
    global $configuration;
    
    if ($mysqli = sqlConnect($configuration["post_db"])) {
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
?>
<?xml version="1.0" ecoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
    <title>Andteitas er teit</title>
</head>
<body>
<header id="header">
    <h1><a href="#">GGR</a></h1>
    <p>~(Gruppen tidligere kjent som Gabe's Game-O-Rama)~</p>
</header>
<div id="menu">
    <ul>
        <li style="border-left: 2px solid red"><a href="#"><p>Home</p></a></li>
        <li><a href="/ihaxu"><p>U got Haxxed</p></a></li>
        <li><a href="/scripts"><p>scripts</p></a>
            <ul>
                <li><a href="/scripts/linux"><p>GNU/Linux</p></a></li>
                <li><a href="/nuthin_here"><p>n/a</p></a></li>
            </ul>
        </li>
        <li><a href="/nuthin_here"><p>Another Element</p></a></li>
    </ul>
</div>
<div id="content">
    <h1>Hvalkom til Geggern</h1>
    <?php
    $posts = pullPosts();

    //Print every post fetched
    foreach ($posts as $post) {
        ?>
        <div id='<?php print($post["ID"]); ?>' class='post'>
            <h3><?php print($post["Title"]); ?></h3>
            <p><?php print($post["Content"]); ?></p>
            <p class="postFooter">Published by <font color='orange'><?php print($post["Author"]); ?></font>
                at <font color='pink'><?php print($post["PublishTime"]); ?></font></p>
        </div>
    <?php } ?>
</div>
</html>