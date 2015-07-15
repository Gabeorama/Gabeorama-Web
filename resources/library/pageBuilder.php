<?php
	//Include configuration
	require_once(realpath(dirname(__FILE__)) . "/../configuration.php");
	
	function buildLayoutWithContent($content, $title = "Gabeorama.org") {
		$fullPath = TEMPLATES_PATH . "/$content";
		
		//Show header
		include_once(TEMPLATES_PATH . "/header.php");
		
		echo("<div id=\"content\">\n");
		
		//Check for file and render
		if (file_exists($fullPath)) {
			include_once($fullPath);
		} else {
			include_once(TEMPLATES_PATH . "/filenotfound.php");
		}
		
		echo("</div>\n");
		
		include_once(TEMPLATES_PATH . "/footer.php");
	}
?>