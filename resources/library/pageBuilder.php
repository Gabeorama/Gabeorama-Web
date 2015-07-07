<?php
	require_once(realpath(dirname(__FILE__)) . "/../configuration.php");
	
	function buildLayoutWithContent($content) {
		$fullPath = TEMPLATES_PATH . "/$content";
		
		//Show header
		require_once(TEMPLATES_PATH . "/header.php");
		
		echo("<div id=\"content\">\n");
		
		//Check for file and render
		if (file_exists($fullPath)) {
			require_once($fullPath);
		} else {
			require_once(TEMPLATES_PATH . "/filenotfound.php");
		}
		
		echo("</div>\n");
		
		require_once(TEMPLATES_PATH . "/footer.php");
	}
?>