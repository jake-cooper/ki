<?php

//function fixtags can tell the difference between XHTML and TAGS inserted in @PARAM $text from the DATABASE 
function fixtags($text){
$text = htmlspecialchars($text);
$text = preg_replace("/=/", "=\"\"", $text);
$text = preg_replace("/&quot;/", "&quot;\"", $text);
$tags = "/&lt;(\/|)(\w*)(\ |)(\w*)([\\\=]*)(?|(\")\"&quot;\"|)(?|(.*)?&quot;(\")|)([\ ]?)(\/|)&gt;/i";
$replacement = "<$1$2$3$4$5$6$7$8$9$10>";
$text = preg_replace($tags, $replacement, $text);
$text = preg_replace("/=\"\"/", "=", $text);
return $text;
}


function queryAPI($qurl){
$json = file_get_contents('.$qurl.'); // this WILL do an http request for you
$data = json_decode($json);
//echo $data->{'token'};
return $data;
}
?>