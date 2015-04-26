<?xml version="1.0" ecoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <link rel="stylesheet" href="../../stylesheet.css"
type="text/css">
	<style type="text/css">
		html, body {
			height: 100%;
			width: 100%;
			margin: 0;
		}
		.overlay {
			margin: .2em .5em;
			position: fixed;
		}
		#overlaya {
			right: 0;
		}
		#overlayb {
			right: 0;
			bottom: 0;
		}
		#overlayc {
			left: 0;
			bottom: 0;
		}
		#overlayd {
			left: 0;
		}
		#countdown {
			position: fixed;
			right: 0;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: -100;
			background-size: cover;
		}
	</style>
        <title>Form for TG 15</title>
</head>
<body>
<?php
	$YEAR = "2014";
	$MONTH = 12;
	$DATE = "1";
	$HOUR = "08";
	$MINUTE = "00";
	$months = array("January", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
	
	$TIME = $DATE . " " . $months[$MONTH-1] . " " . $YEAR . " " . $HOUR . $MINUTE;
	//$tim = $DATE . " " . $months[$MONTH-1] . " " . $YEAR . " " . $HOUR . $MINUTE;
	//echo strtotime($tim);
	
	if (time() < strtotime($TIME)) {
		echo "<center>This poll will not open until " . $TIME . " (subject to change)<br /></center>\n";
		echo "<frameset rows='100%, *' frameborder=no framespacing=0 border=0>\n";
		//echo "<frame src=\"http://gabeorama.org/slt.html\" name=mainwindow frameborder=no framespacing=0 marginheight=0 marginwidth=0></frame>";
		echo "<div id=\"countdown\"><iframe src=\"http://www.7is7.com/otto/countdown.html?year=" . $YEAR . "&month=" . $MONTH . "&date=" . $DATE . "&ts=24&hrs=" . $HOUR . "&min=" . $MINUTE . "&sec=0&tz=60&lang=en&show=dhms&mode=r&cdir=down&bgcolor=%23000000&fgcolor=%23FFFFFF&title=Countdown%20To\" name=main frameborder=no framespacing=0 marginheight=0 marginwidth=0 style=\"width: 100%; height: 100%\"></iframe></div>\n";
		echo "</frameset>\n";
		echo "<div class=\"overlay\" id=\"overlayc\"><img src=\"../../images/Yuno1.png\" /></div>\n";
		echo "<div class=\"overlay\" id=\"overlayb\"><img src=\"../../images/Neptune2.png\" style=\"width: 400px; height: 250px;\"/></div>\n";
		echo "<div class=\"overlay\" id=\"overlaya\"><img src=\"../../images/Ryuuko1.png\" style=\"width:251px; height: 400px;\" /></div>\n";
		echo "<div class=\"overlay\" id=\"overlayd\"><img src=\"../../images/Himeko1.png\" style=\"width: 300px; height: 260px;\" /></div>\n";
		echo "</body>\n";
		echo "</html>";
		die();
	}
?>
<div id="formContainer" style="margin: auto; width: 1000px;">
        <form id="form" action="http://gabeorama.org/poll/submit.php" method="post">
        	<input type="hidden" name="formID" value="formTG15" />
                <h3>Generell informasjon</h3>
                Namn:             <input type="text" name="name" /><span style="color: red">*</span><br />
                Geekeventsbruker: <input type="text" name="geekevents" /><span style="color: red">*</span> (Epost, brukernamn eller telefon)<br />
                Køplasser:        <input type="text" name="queue" /> <span style="color: red;">*</span> Separate numbers with a ","
                <h3>Seteprefferanser</h3>
                <p>Har kan du spessifisere spessielle eller generelle ønsker for setevelgingen. Dersom det er veldig stor uenighet om seteområdene (spessielt mellom stillesoner og bråkesoner) kan det hende det blir en splitting.</p>
                <br />
                Førsteprioritert seteområde: <select name="seatZonePreffered" size=1>
                        <option value="none">-</option>
                        <option value="zone1">Sone 1</option>
                        <option value="zone2">Sone 2</option>
                        <option value="zone3">Sone 3</option>
                        <option value="zone4">Sone 4</option>
                        <option value="zone5">Sone 5</option>
                        <option value="zone6">Sone 6</option>
                        <option value="silentAny">Stille, idrgaf</option>
                        <option value="nonSilentAny">Bråk, idrgaf</option>
                </select>
                <br />
                Andreprioritert seteområde: <select name="seatZoneSecondary" size=1>
                        <option value="none">-</option>
                        <option value="zone1">Sone 1</option>
                        <option value="zone2">Sone 2</option>
                        <option value="zone3">Sone 3</option>
                        <option value="zone4">Sone 4</option>
                        <option value="zone5">Sone 5</option>
                        <option value="zone6">Sone 6</option>
                        <option value="silentAny">Stille, idrgaf</option>
                        <option value="nonSilentAny">Bråk, idrgaf</option>
                </select>
                <br />
                Er du for en eventuell splitting: 
                <input type="radio" name="zoneSplit" value="yes" />Ja
                <input type="radio" name="zoneSplit" value="no" />Nei
                <input type="radio" name="zonesplit" value="none" checked/>IDGAF<br /><br />

                <p>Her har du muligheten til å presisere et par kamerater du har spessielt lyst til å sitte i nærheten. Skapere har et eget felt, ellers kan det være lurt å skrive opp mer enn ett alternativ.</p><br />
		
                SKAPERE: <input type="text" name="skapers"><br /><br />
		
                Sittekamerat: <input type="text" name="seatBudA" /><br />
                Sittekamerat: <input type="text" name="seatBudB" /><br />
                Sittekamerat: <input type="text" name="seatBudC" /><br />
                Sittekamerat: <input type="text" name="seatBudD" /><br />

                <h3>Ekstra informasjon</h3>
                <p>Transport får man nesten fikse på egenhånd, men alt annet som burde vites om kan skrives her.
                <textarea name="extra" rows=10 cols=100></textarea><br /><br />

                <input name="submit" type="submit" value="send" />
        </form>
</div>
</body>
</html>
