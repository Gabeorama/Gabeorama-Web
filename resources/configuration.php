<?php 
$fullPath = realpath(dirname(__FILE__)) . "/configuration.json";
if (!file_exists($fullPath)) die ("Unable to fetch configuration. File not found: configuration.json");

$version = "v0.2";

#Load configurations
$configuration = json_decode(file_get_contents($fullPath));

defined("LIBRARY_PATH")
	or define("LIBRARY_PATH", realpath(dirname(__FILE__)) . "/library");

defined("TEMPLATES_PATH")
	or define("TEMPLATES_PATH", realpath(dirname(__FILE__)) . "/templates");
	
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRICT);
?>
