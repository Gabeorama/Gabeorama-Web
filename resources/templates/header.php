<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	global $configuration;
	$homeUrl = $configuration->urls->baseUrl;
    $currURL = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//<?php print($_SERVER['HTTP_HOST']); ?>/css/stylesheet.css" type="text/css">
    <title><?php echo($title); ?></title>
</head>
<body>
<?php print($currURL); ?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="//<?php print($_SERVER['HTTP_HOST']); ?>">Gabeorama Web</a>
        </div>
        <div id="navbar" class="navbar-collapse-collapse">
            <ul class="nav navbar-nav">
                <li <?php if ($currURL=='/') { print('class="active"'); } ?>><a href="//<?php print($_SERVER['HTTP_HOST']); ?>">Home</a></li>
                <li <?php if ($currURL=='/survey/') print('class="active"'); ?>><a href="//<?php print($_SERVER['HTTP_HOST']); ?>/surveys/">Surveys</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Scripts <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="//<?php print($_SERVER['HTTP_HOST']); ?>/scripts/linux">Linux</a></li>
                    </ul>
                </li>
                <li><a href="/events/">Events</a></li>
                <li <?php if ($currURL == '/kino/') print('class="active"'); ?>><a href="//<?php print($_SERVER['HTTP_HOST']); ?>/kino/">Kino Watch</a></li>
            </ul>
            <?php if (isset($_SESSION["username"])) { ?>
                <div class="nav navbar-nav navbar-right">
                    <li><a href="//<?php print($_SERVER['HTTP_HOST']); ?>/profile/">Logged in as <font color="orange"><?php print($_SESSION["username"]);?></font></a></li>
                    <li><a href="//<?php print($_SERVER['HTTP_HOST']); ?>/logout/">Log Out</a></li>
                </div>
            <?php } else { ?>
            <form class="navbar-form navbar-right" action="/login/" method="POST">
                <div class="form-group">
                    <input name="username" type="text" placeholder="Username/Email" class="form-control">
                </div>
                <div class="form-group">
                    <input name="password" type="password" placeholder="Password" class="form-control">
                </div>
                <button type="submit" name="submit" class="btn btn-success">Sign in</button>
            </form>
            <?php } ?>
        </div>
    </div>
    <!--<div id="usernameBox">
        <?php
        if (isset($_SESSION["username"])) {
            ?>
            Logged in as <font color="orange"><?php print($_SESSION["username"])?></font>. <a href="/logout/">log out</a>
            <?php
        } else {
            ?>
            Not logged in. <a href="/login/">Log in</a>
            <?php
        }
        ?>
    </div>-->
</nav>

