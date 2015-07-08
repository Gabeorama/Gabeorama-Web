<h1>Hvalkom til Geggern</h1>
<?php
    $posts = pullPosts();

    //Print every post fetched
    foreach ($posts as $post) {
        ?>
        <div id='<?php print($post["ID"]); ?>' class='post'>
            <h3><?php print($post["Title"]); ?></h3>
            <p><?php print($post["Content"]); ?></p>
            <p class="postFooter">Published by <font color='orange'><?php print($post["Author"]); ?></font>
                at <font color='pink'><?php print($post["PublishTime"]); ?></font></p>
        </div>
    <?php } ?>