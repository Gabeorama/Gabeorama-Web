<?php
require_once(realpath(dirname(__FILE__) . "/../configuration.php"));
require_once(LIBRARY_PATH . "/surveys.php");
require_once(LIBRARY_PATH . "/accounts.php");

function date_to_countDown($date) {
    $future = new DateTime($date);
    $now = new DateTime();

    return $future->diff($now)->format("<span class='days'>%a</span>days, <span class='hours'>%h</span>:<span class='minutes'>%i</span>:<span class='seconds'>%s</span>");
}

$surveys = getSurveys();
if (count($surveys) > 0 && $surveys !== false) { ?>
    <div class="alert alert-info" xmlns="http://www.w3.org/1999/html">Do you want to <a href="create/">create</a> a survey?<br/>It's easy!</div>
    <?php foreach ($surveys as $survey): ?>
        <div class="panel panel-default" onclick="window.open('//<?php print($_SERVER['HTTP_HOST']); ?>/survey/answer/<?php print($survey['Random_ID']); ?>', '_blank');">
            <div class="panel-heading"><h3><?php print($survey["Title"]); ?></h3>
                <div class="countDownContainer">Time left: <span class="countDown"><?php print(date_to_countDown($survey["ExpirationTime"])); ?></span></div></div>
            <div class="panel-body"><?php print($survey["Description"]); ?></div>
            <div class="panel-footer">Added by <span style="color: #ffA500"><?php print(getUser(intval($survey["Author_ID"]))["username"]); ?></span> at <span style="color: #ffc0cb"><?php print($survey["CreationTime"]) ?></span></div>
        </div>
        <?php
    endforeach; ?>
    <script type="text/javascript" src="/js/countdown.js"></script>
<?php } else {?>
    <div class="alert alert-danger">Unfortunately, there are no available surveys at this time.<br/>Why not <a href="create/">create</a> one?</div>
<?php } ?>