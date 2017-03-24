<?php
function getCodes() {
     return array(
         '' =>      array("type" => BBCODE_TYPE_ROOT,
             "childs" => "!i"),
         "i"=>      array("type" => BBCODE_TYPE_NOARG,
             "open_tag" => "<i>",
             "close_tag" => "</i>",
             "childs" => "b"),
         "url"=>    array("type" => BBCODE_TYPE_OPTARG,
             "open_tag"=> "<a href='{PARAM}'>",
             "close_tag" => "</a>",
             "default_arg"=>"{CONTENT}",
             "childs" => "b,i"),
         "img"=>    array("type" => BBCODE_TYPE_NOARG,
             "open_tag" => "<img src='",
             "close_tag" => "' />",
             "childs" => ""),
         "b" =>     array("type" => BBCODE_TYPE_NOARG,
             "open_tag"=> "<b>",
             "close_tag"=> "</b>")
     );
}

function parse($text) {
    $bbcodes = getCodes();
    $bbcode = bbcode_create($bbcodes);
    return bbcode_parse($bbcode, $text);
}