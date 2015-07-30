<?php
session_start();
require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
require_once(LIBRARY_PATH . "/pageBuilder.php");
require_once(LIBRARY_PATH . "/accounts.php");
require_once(LIBRARY_PATH . "/posts.php");

$form = array(
	"title" => "Publish post",
	"name" => "publish",
	"action" => "publish.php",
	"method" => "POST",
	"submitText" => "Post",
	"formObjects" => array(
		"title" => array(
		    "text" => "Post title: ",
		    "type" => "text"
		),
		"content" => array(
			"text" => "Post content: ",
			"type" => "textarea",
            "rows" => 15,
            "cols" => 50
		),
        "type" => array(
            "text" => "",
            "type" => "hidden",
            "value" => "NEWS"
        )
	)
);
if (isset($_POST["submit"]) && isset($_SESSION["username"])) {
    $user = getUser($_SESSION["ID"]);
    
    if ($user["group"] == "admin") {
        if ($result = createPost($user["username"], $_POST["title"], $_POST["content"], $_POST["type"])) {
            buildLayoutWithContent("contentpage.php", "Post published", array(
                "title" => "Post published",
                "pageContent" => "Your post has been successfully posted."));
        } else {
            buildLayoutWithContent("contentpage.php", "Error publishing", array(
                "title" => "Error",
                "pageContent" => "There was an error publishing your post."));
        }
    } else {
        //not permitted
        buildLayoutWithContent("contentpage.php", "Not permitted", array(
            "title" => "You are not permitted to do that",
            "pageContent" => "Please contact a site operator if you feel this is wrong."));
    }
//Check if already logged in
} elseif (isset($_SESSION["username"])) {
	buildLayoutWithContent("form_template.php", "Gabeorama - publish", array("form" => $form));
//Not logged in, redirect to login
} else {
	buildLayoutWithContent("contentPage.php", "You need to be logged in", array(
		"title" => "You need to log in",
		"pageContent" => "You need to <a href=\"login.php\">login</a> or <a href=\"register.php\">register</a> to do that."));
}
?>