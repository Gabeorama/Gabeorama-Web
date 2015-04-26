<?php
	if (!isset($_GET["mult"])) {
		echo "<form action=\"gulrot.php\" method=\"GET\">";
		echo "Select a number to do magux with <input type=\"text\" name=\"mult\" />";
		echo "<input type=\"submit\" value=\"go\"/>";
		echo "</form>";
	} else {
		if (is_numeric($_GET["mult"])) {
			//echo "Maggux made " . 
			$magux = ((intval($_GET["mult"]) * 5 + 2 % 15) - 53);
			echo "MAGGUX made {$magux}";
		} else {
			echo "You are really bad at writing numbers. noob";
		}
	}
?>
