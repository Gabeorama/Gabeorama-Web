<?php
session_start();
session_destroy();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
buildLayoutWithContent("contentPage.php", "Logged out", array(
	"title" => "Logged out",
	"pageContent" => 'You have been logged out.
		<script type="text/javascript">
			alert("you have been logged out");
			history.back();
		</script>
		<noscript>
			<a href="' . $configuration->urls->baseUrl . '">Go back</a>
		</noscript>'
	));
?>