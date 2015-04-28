<!DOCTYPE html>
<html>
<head>
    
</head>

<body>
<table border=2 id="event_table">
    <tr>
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">Time</th>   
    </tr>
    
<?php
include "event.php";

foreach (getEventsAfter(date("Y-m-d H:i:s")) as $event) {
    printf("\t<tr>\n"
           . "\t\t<th>%s</th>\n"
           . "\t\t<th>%s</th>\n"
           . "\t\t<th>%s</th>\n"
           . "\t</tr>\n", $event[2], $event[3], $event[4]);
}
?>
</table>
</body>
</html>
    