<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/surveys.php");
require_once(LIBRARY_PATH . "/accounts.php");

$surveys = getSurveys();
if (count($surveys) > 0 && $surveys !== false) {
    foreach ($surveys as $survey): ?>
        <div class="panel panel-default">
            <div class="panel-header"><?php print($survey["Title"]); ?> - Time left: <?php print($survey["ExpirationTime"]); ?></div>
            <div class="panel-body"><?php print($survey["Description"]); ?></div>
            <div class="panel-footer">Added by <?php print(getUser($survey["Author_ID"])["username"]); ?> at <?php print($survey["CreationTime"]) ?></div>
        </div>
        <?php
    endforeach;
} else {?>
    <div class="alert alert-danger">Unfortunately, there are no available surveys at this time.<br/>Why not <a href="create/">create</a> one?</div>
<?php } ?>