<?php
	require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
	
	$title = "Gabeorama.org - Gulrot php maggux";
	include_once(TEMPLATES_PATH . "/header.php");
	
	echo("<div id=\"content\">");

	if (isset($_GET["mult"])) {
		if (is_numeric($_GET["mult"])) {
			//echo "Maggux made " . 
			$magux = ((intval($_GET["mult"]) * 5 + 2 % 15) - 53);
			echo "MAGGUX made {$magux}";
		} else {
			echo "You are really bad at writing numbers. noob";
		}
	}
	echo "<form action=\"gulrot.php\" method=\"GET\">";
	echo "Select a number to do magux with <input type=\"text\" name=\"mult\" />";
	echo "<input type=\"submit\" value=\"go\"/>";
	echo "</form>";
	
	echo("</div>");
	
	include_once(TEMPLATES_PATH . "/footer.php");
?>
