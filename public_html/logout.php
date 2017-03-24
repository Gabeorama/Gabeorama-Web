<?php
session_start();
session_destroy();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
buildLayoutWithContent("contentPage.php", "Logged out", array(
	"title" => "Logged out",
	"pageContent" => 'You have been logged out.
		<noscript>
			<a href="' . $configuration->urls->baseUrl . '">Go back</a>
		</noscript>'
	));
?>