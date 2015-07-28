<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require(LIBRARY_PATH . "/posts.php");

buildLayoutWithContent("frontpage.php", "Gabeorama.org - Front page");
?>