<?php

session_start();
session_destroy();

?>
You have been logged out.
<button onClick="history.back()">Go back</button>