<?php
$configuration = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "Gabeorama-Web/configuration.ini");
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
            $arr = $result->fetch_all();
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
		<p>Gabe's Game-O-Rama</a>
	</header>
	<div id="menu">
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="/ihaxu">U got Haxxed</a></li>
			<li><a href="/scripts">scripts</a>
				<ul>
					<li><a href="/scripts/linux">GNU/Linux</a></li>
					<li><a href="/nuthin_here">n/a</a></li>
				</ul>
			</li>
			<li><a href="/nuthin_here">Another Element</a></li>
		</ul>
	</div>
	<div id="content">
		<h1>Whalecum to Gee Gee Arr</h1>		
<?php
$posts = pullPosts();
foreach ($posts as $post) {
    printf("<div id='%s' class='post'>\n"
          . "\t<h3>%s</h3>\n"
          . "\t<p>%s</p>\n"
          . "\t<p class='postFooter'>Published by <font color='orange'>%s</font> at <font color='pink'>%s</font></p>\n"
          . "</div>\n",
          $post[1], $post[4], $post[6], $post[3], $post[5]);
}
?>
    </div>
</html>
