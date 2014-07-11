<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset="utf-8">
	<title>WebPay</title>
	
	<style>
	  #J7_1  {  color: '#5D9CEC'; }
	  #J7_2  {  color: '#48CFAD'; }
	  #J7_3  {  color: '#FFCE54'; }
	  #J7_4  {  color: '#FC6E51'; }
	</style>
 	
 	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
<h1>DEBUGS Daffy</h1>



<script>
	$(function () {
		tv.slideshow.start();
	});
</script>
</body>
</html>


<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
 
// Your PHP code that's being debugged below ↓

?>

<?php
// Creating a semi-complicated-to-follow object
$object = new stdClass();
$object->key1 = "Hello";
$object->key1 = array("world", "!");
$object->key2 = "my";
$object->key3 = array("name", "is", array("name" => "Riley"), array("punc" => "!"));

// Var dumping PHP $object
// ** I wrote the closing "<pre>" unconventionally because my syntax highlighter uses "<pre>" tags in it's markup
echo '<pre>'.var_dump($object).'<'.'/'.'pre>';
//Result:
/*
object(stdClass)#1 (3) { ["key1"]=> array(2) { [0]=> string(5) "world" [1]=> string(1) "!" } ["key2"]=> string(2) "my" ["key3"]=> array(4) { [0]=> string(4) "name" [1]=> string(2) "is" [2]=> array(1) { ["name"]=> string(5) "Riley" } [3]=> array(1) { ["punc"]=> string(1) "!" } } }
*/
?>

<?php
// Creating a semi-complicated-to-follow object
$object = new stdClass();
$object->key1 = "Hello";
$object->key1 = array("world", "!");
$object->key2 = "my";
$object->key3 = array("name", "is", array("name" => "Riley"), array("punc" => "!"));

// Var dumping PHP $object 
// ** I wrote the closing "<pre>" unconventionally because my syntax highlighter uses "<pre>" tags in it's markup
echo '<pre>'.json_encode($object).'<'.'/'.'pre>';
//Result:
/*
{
  "key1": [
    "world",
    "!"
  ],
  "key2": "my",
  "key3": [
    "name",
    "is",
    {
      "name": "Riley"
    },
    {
      "punc": "!"
    }
  ]
}
*/?>
<!--
http://jsoneditoronline.org
http://jsonviewer.stack.hu-->