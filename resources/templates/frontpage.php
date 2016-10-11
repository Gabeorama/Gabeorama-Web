<?php require_once(realpath(dirname(__FILE__)) . "/../library/BBCode.php");
?>

<h1>Hvalkom til Geggern</h1>
<?php
    $posts = pullPosts();

    //Print every post fetched
    foreach ($posts as $post) {
        ?>
        <div id='<?php print($post["ID"]); ?>' class='panel panel-default'>
            <div class="panel-heading"><?php print($post["Title"]); ?></div>
            <div class="panel-body"><?php print(parse(htmlspecialchars($post["Content"]))); ?></div>
            <div class="panel-footer">Published by <font color='orange'><?php print($post["Author"]); ?></font>
                at <font color='pink'><?php print($post["PublishTime"]); ?></font></div>
        </div>
    <?php } ?>