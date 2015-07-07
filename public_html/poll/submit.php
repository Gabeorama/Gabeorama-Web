<head>
  <link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<?php
	/* */
	/* DISCLAIMER: THIS IS A TERRIBLY BADLY WRITTEN PHP FILE, AND EXISTS PURELY FOR HAVING A COMPLETE OVERVIEW */
	/* */
        $EDIT_INVALID_FORMID = "formIDInvalid";
	$EDIT_INVALID_USER = "userInvalid";
	$EDIT_INVALID_PASSWORD = "passwordInvalid";
	$EDIT_ACCEPTED = "accepted";

	if(!isSetValid("formID") || isset($_GET["edit"])) {
		echo "<h1>Edit/view replies</h1>";
		$formID = $_GET["edit"];
		$uid = $_GET["uid"];
		$ppw = $_GET["ppw"];
		$validate = validateFormEdit($formID, $uid, $ppw);
		if ($validate == $EDIT_ACCEPTED) {
			$settings = loadUserInfo($formID, $uid);
			
			$keys = array_keys($settings);
			
			echo "<form method=\"POST\" action=\"submit.php?edited=1&confirm=1&uid=" . $uid . "&ppw=" . $ppw . "\">\n";
			echo "<input type=\"hidden\" name=\"formID\" value=\"" . $formID . "\">";
			echo "<table border=\"1\">\n";
			
			foreach($keys as $key) {
				if ($key != "" && $key != "pid" && $key != "ppw") {
					echo "<tr><th>" . $key . "</th><th><input type=\"" . ($key == "extra" ? "textarea" : "text") . "\" name=\"" . $key . "\" value=\"" . $settings[$key] . "\"></th></tr>\n";
				}
			}
			
			echo "</table>\n";
			echo "<input type=\"submit\" value=\"send\">\n";
			
		} else {
			if ($formID != "" || $uid != "" | $ppw != "") {
				echo "<span style=\"color: red;\">Some of the information you supplied was not accepted</span><br />\n";
			} else {
				//Don't throw errors at new/empty submits
				$validate = "";
			}
			echo "Please enter the information for the response you want to edit\n";
			echo "<form method=\"GET\" action=\"submit.php\">\n";
			echo "<table>\n";
			echo "<tr" .
				(strpos($validate, $EDIT_INVALID_FORMID) !== false ? " style=\"color: red;\"" : "") .
				"><th>Form ID:</th><th><input type=\"text\" name=\"edit\" value=\"" . $formID . "\" /></th>" .
				(strpos($validate, $EDIT_INVALID_FORMID) !== false ? "<th><span style=\"color: red;\">The entered formID was not found</span></th>" : "") . "</tr>\n";
			echo "<tr" .
				(strpos($validate, $EDIT_INVALID_USER) !== false ? " style=\"color: red;\"" : "") .
				"><th>User ID:</th><th><input type=\"text\" name=\"uid\" value=\"" . $uid . "\"/></th>" .
				(strpos($validate, $EDIT_INVALID_USER) !== false ? "<th><span style=\"color: red;\">This user was not found</span></th>" : "") . "</tr>\n";
			echo "<tr" .
				(strpos($validate, $EDIT_INVALID_PASSWORD) !== false ? " style=\"color: red;\"" : "") .
				"><th>Password:</th><th><input type=\"text\" name=\"ppw\" value=\"" . $ppw . "\" /></th>" .
				(strpos($validate, $EDIT_INVALID_PASSWORD) !== false ? "<th><span style=\"color: red;\">This password did not match the user</span></th>" : "") . "</tr>\n";
			echo "</table>\n";
			echo "<input type=\"submit\" value=\"Edit\" />\n";
			echo "</form>";
		}
	}
	$formID = htmlspecialchars($_POST["formID"]);
	echo "<h1> Info for form " . $formID . "</h1>";
	if ($formID == "formTG15") {
		if (!(isSetValid("name") && isSetValid("geekevents") && isSetValid("queue"))) {
			echo "You left out required fields, please go back and verify your information <br />";
			die();
		} else {
			/*REQUIRED VALUES*/
			$name = $_POST["name"];
			$geekevents = $_POST["geekevents"];
			$queue = explode(",", str_replace(" ", "", $_POST["queue"]));
			
			//Validate queue input
			foreach ($queue as $que) {
				//Places are numbers
				if (!is_numeric($que)) {
					echo "You entered some invalid input, please go back and verify <br />";
					echo "Invalid input was queue: \"" . htmlspecialchars($que) . "\"; not a number <br />";
					die();
				}
			}
			
			/*OPTIONAL VALUES*/
			//Add all possible zone selections for verification
			$zones = array("zone1", "zone2", "zone3", "zone4", "zone5", "zone6", "silentAny", "nonSilentAny");
			
			//Compare input to possible answers, never trust the user
			$seatZonePrimary = (isSetValid("seatZonePreffered") && in_array($_POST["seatZonePreffered"], $zones) ? $_POST["seatZonePreffered"] : "N/A");
			$seatZoneSecondary = (isSetValid("seatZoneSecondary") && in_array($_POST["seatZoneSecondary"], $zones) ? $_POST["seatZoneSecondary"] : "N/A");
			$zoneSplit = (issetvalid("zoneSplit") && in_array($_POST["zoneSplit"], array("yes", "no")) ? $_POST["zoneSplit"] : "IDGAF");
			$skapers = (issetvalid("skapers") ? $_POST["skapers"] : "N/A");
			$buds = array();
			
			if (issetvalid("buds")) {
				$buds = $_POST["buds"];
			}
			
			//contract seatBud{A-D} to an array
			foreach(array("A", "B", "C", "D") as $bud) {
				if (issetvalid("seatBud" . $bud)) {
					$buds = array_merge($buds, array($_POST["seatBud" . $bud]));
				}
			}
			//Default to N/A
			if (!isset($buds) || $buds == "") {
				$buds = array("N/A");
			}
			
			$extra = (issetvalid("extra") ? $_POST["extra"] : "N/A");
			
			if (isset($_GET["confirm"]) && $_GET["confirm"] == "1") {
				/*SAVE CONFIRMED VALUES TO FILE*/
				$path = "r/" . $formID;
				
				//Verify that the path is writeable by php
				if(!is_writeable($path)) {
					echo $path . " was unreadable to php <br />";
				}
				
				//Read save file
				if (!$rHandle = fopen($path, "r")) {
					echo "Could not read file for verification";
					die();
				}
				
				//Read and close handle
				$save = fread($rHandle, filesize($path));
				fclose($rHandle);
				
				//Check if the file contains the submitted info
				if ((strstr($save, "geekevents: " . $geekevents)) && (!isset($_GET["edited"]) || $_GET["edited"] != "1")) {
					echo "ERROR: This geekevent user has already been submitted to the system";
					die();
				}
				
				//Bind save file to handle or throw error
				if (!$handle = fopen($path, "a")) {
					echo "ERROR WRITING TO DISK";
					die();
				}
				
				if (isset($_GET["edited"])) {
					$pid = $_GET["uid"];
					$ppw = $_GET["ppw"];
					if (validateFormEdit($formID, $pid, $ppw) == $EDIT_ACCEPTED) {
						removeField($formID, $pid);
					} else {
						echo "Error authenticating edit, please go back and try again";
						die();
					}
				} else {
					$pid = generateRandom(10);
					$ppw = generateRandom(10);
				}
				
				$write[0] = "pid: " . $pid;
				$write[1] = "ppw: " . $ppw;
				$write[2] = "name: " . $name;
				$write[3] = "geekevents: " . $geekevents;
				$write[4] = "queue: " . implode(", ", $queue);
				$write[5] = "seatZonePreffered: " . $seatZonePrimary;
				$write[6] = "seatZoneSecondary: " . $seatZoneSecondary;
				$write[7] = "zoneSplit: " . $zoneSplit;
				$write[8] = "skapers: " . $skapers;
				$write[9] = "buds: " . implode(", ", $buds);
				$write[10] = "extra: " . $extra;
				
				$settings = "";
				
				foreach ($write as $opt) {
					$opt = str_replace("\\", "\\\\", $opt);
					$opt = str_replace(":", "&#58", $opt);
					$opt = preg_replace('/&#58/', ':', $opt, 1);
					$settings .= $opt . " \n";
				}
				
				//Escape additional open/close braces
				$settings = str_replace("}", "&#125", $settings);
				$settings = str_replace("{", "&#123", $settings);
				
				if (fwrite($handle, "{\n" . $settings . "}\n") === FALSE) {
					echo "ERROR WRITING";
				}
				
				//Close handle
				fclose($handle); 
				
				echo "<h3>kthxbai</h3>";
				echo "Should you ever need to edit anything, your username is <span style=\"color: red\">" . $pid . "</span> and your password is <span style=\"color: red;\">" . $ppw . "</span> for form ID <span style=\"color: green;\">" . $formID . "</span>.<br />";
				echo "Alternatively, you can follow <a href=\"http://gabeorama.org/poll/submit.php?edit=" . $formID . "&uid=" . $pid . "&ppw=" . $ppw . "\">this link</a>.\n";
			} else {
			
				/*DISPLAY VALUES FOR CONFIRMATION*/
				echo "<h2>Please verify your information</h2>\n";
				/*DISPLAY REQUIRED VALUES*/
				
				echo "<h3>General Info</h3>\n";
				echo "<table class=\"keyTable\" border=\"1\">";
				colorvalue("Name", $name);
				colorvalue("Geekevents contact info", $geekevents);
				echoarray ("Place" . (count($queue) > 1 ? "s" : "") . " in queue: ", $queue);
				echo "</table>";
			
				/*DISPLAY OPTIONAL VALUES*/
				echo "<h3>Seating prefferences</h3>\n";
				echo "<table class=\"keyTable\" border=\"1\">";
				colorvalue("Primary zone choice", $seatZonePrimary);
				colorvalue("Secondary zone choice", $seatZoneSecondary);
				colorvalue("Zone Split", $zoneSplit);
				colorvalue("Skapers", $skapers);
				echoarray("Preffered seating m8s", $buds);
				colorvalue("Extra infos", $extra);
				echo "</table>";
				
				/*REBUILD FORM*/
				echo "<form method=\"POST\" action=\"submit.php?confirm=1\">\n";
				echo "<input type=\"hidden\" name=\"formID\" value=\"" . $formID . "\" />\n";
				echo "<input type=\"hidden\" name=\"name\" value=\"" . $name . "\" />\n";
				echo "<input type=\"hidden\" name=\"geekevents\" value=\"" . $geekevents . "\" />\n";
				echo "<input type=\"hidden\" name=\"queue\" value=\"" . implode(",", $queue) . "\" />\n";
				echo "<input type=\"hidden\" name=\"seatZonePreffered\" value=\"" . $seatZonePrimary . "\" />\n";
				echo "<input type=\"hidden\" name=\"seatZoneSecondary\" value=\"" . $seatZoneSecondary . "\" />\n";
				echo "<input type=\"hidden\" name=\"zoneSplit\" value=\"" . $zoneSplit . "\" />\n";
				$arr = array("A", "B", "C", "D");
				for ($i = 0; $i < count($arr); $i++) {
					echo "<input type=\"hidden\" name=\"seatBud" . $arr[$i] . "\" value=\"" . (count($buds) > $i ? $buds[$i] : "") . "\" />\n";
				}
				echo "<input type=\"hidden\" name=\"extra\" value=\"" . $extra . "\" />\n";
				echo "<input type=\"submit\" value=\"Confirm\" />\n";
				
				echo "</form>\n";
				echo "</div>\n";
			}
		}
	}
	
	function isSetValid($tag) {
		return (isset($_POST[$tag]) && ($_POST[$tag] != ""));
	}
	
	function colorValue($key, $value) {
		echo "<tr>\n\t<th class=\"formKey\">" . $key . "</th><th class=\"formValue\" style=\"color: " . ($value == "N/A" ? "red" : "green") . "\">" . htmlspecialchars($value) . "</th>\n</tr>\n";
	}
	
	function echoArray($key, $arr) {
		echo "<tr>\n<th class=\"formKey\">" . $key . "<th class=\"formValue\">";
		
		for ($i = 0; $i < count($arr); $i++) {
			//color N/A in red, everything else in green
			echo "<span style=\"color: " . ($arr[$i] == "N/A" ? "red" : "green") . "\">" . $arr[$i] . "</span>";
	
			if ($i == count($arr) - 1) {
				echo "</th></tr>\n";
			} else {
				echo ", ";
			}
		}
	}
	
	function generateRandom($len) {
	
		$validChars = "abcdefghijklmnopqrstuvwxyz1234567890";
		$result = "";
		
		for ($i = 0; $i < $len; $i++) {
			$nextChar = mt_rand(0, strlen($validChars)-1);
			$result .= $validChars[$nextChar];
		}
		
		return $result;
	}
	
	function validateFormEdit($ID, $UID, $PWD) {
		global $EDIT_ACCEPTED;
		global $EDIT_INVALID_FORMID;
		global $EDIT_INVALID_USER;
		global $EDIT_INVALID_PASSWORD;
		
		if (!is_readable("r/" . $ID)) return $EDIT_INVALID_FORMID;
		
		$content = file_get_contents("r/" . $ID);
		
		//IDS and passes come with 10 length, anything else is a mismatch
		if (strlen($UID) != 10) return $EDIT_INVALID_USER;
		if (strlen($PWD) != 10) return $EDIT_INVALID_PASSWORD;
		if (strpos($content, "pid: " . $UID) == false) return $EDIT_INVALID_USER;
		
		//Match password with username
		$content = explode("}", $content);
		foreach ($content as $input) {
			if (strpos($input, "pid: " . $UID) !== false)  {
				if (strpos($input, "ppw: " . $PWD) == false) return $EDIT_INVALID_PASSWORD;
			}
		}
		
		//Nothing went wrong, accept
		return $EDIT_ACCEPTED;
	}
	
	function loadUserInfo($ID, $UID) {
		$returnArr = [];
		$content = explode("}", file_get_contents("r/" . $ID));
		foreach ($content as $input) {
			$input = substr($input, 3);
			if (strpos($input, "pid: " . $UID) !== false) {
				//Split the lines
				$lines = explode(" \n", $input);
				foreach ($lines as $line) {
					$arr = explode(": ", $line);
					$returnArr[$arr[0]] = $arr[1];
				}
				return $returnArr;
			}
		}
	}
	
	function removeField($ID, $UID) {
		$newfile = "";
		
		$content = explode("}", file_get_contents("r/" . $ID));
		foreach ($content as $input) {
			$input = substr($input, 3);
			
			if (strpos($input, "pid: " . $UID) !== false || $input == "") {
			} else {
				$newfile .= "{\n" . $input . "}\n";
			}
		}
		
		$handle = fopen("r/" . $ID, "w");
		fwrite($handle, $newfile);
		fclose($handle);
	}
?>
