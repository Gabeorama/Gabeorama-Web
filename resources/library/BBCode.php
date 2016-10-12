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
             "close_tag"=> "</b>"),
         "quote" => array("type" => BBCODE_TYPE_OPTARG,
             "open_tag" => "<div class=\"panel panel-default\"><div class='panel-heading' style='color: #808080 !important;'><i style='color: #ffa500'>{PARAM}</i> wrote:</div><div class='panel-body>'",
             "close_tag" => "</div></div>",
             "default_arg"=>"somebody",
             "childs" => "b,i,url,img")
     );
}

function parse($text) {
    $bbcodes = getCodes();
    $bbcode = bbcode_create($bbcodes);
    return bbcode_parse($bbcode, $text);
}