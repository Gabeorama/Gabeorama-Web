<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/surveys.php");

$surveys = getSurveys();
foreach ($surveys as $survey): ?>
    <div class="panel"></div>
<?php
endforeach;