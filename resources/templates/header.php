<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	global $configuration;
	$homeUrl = $configuration->urls->baseUrl;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <link rel="stylesheet" href="css/stylesheet.css" type="text/css">
    <title><?php echo($title); ?></title>
</head>
<body>
<header id="header">
    <h1><a href="<?php echo("$homeUrl"); ?>">GGR</a></h1>
    <p>~(Gruppen tidligere kjent som Gabe's Game-O-Rama)~</p>
</header>
<div id="usernameBox">
        <?php
    if (isset($_SESSION["username"])) {
        ?>
        Logged in as <font color="orange"><?php print($_SESSION["username"])?></font>. <a href="logout.php">log out</a>
        <?php
    } else {
        ?>
        Not logged in. <a href="<?php echo("login.php?source=//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>">Log in</a>
        <?php
    }
    ?>
    </div>
<div id="menu">
    <ul>
        <li style="border-left: 2px solid red"><a href="<?php echo $homeUrl;?>"><p>Home</p></a></li>
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