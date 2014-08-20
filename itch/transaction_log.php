<?php

require("../../config.php");
require_once("$CFG->dirroot/enrol/iinterswitch/enrol.php");


$context = get_context_instance(CONTEXT_SYSTEM, $id);
require_capability('moodle/course:create', $context);

require_login();               //make sure user is logged in
print_header();                //Show the moodle header

/////////////////////////////////////////////////edit connection param


$db_host = 'localhost';
$db_user = 'user';
$db_pwd = 'pass';
$database = 'your_moodle_database';
$table = 'paymentlog_id';


/////////////////////////////////////////////////connection params ends

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

// sending query
$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Table: INTERSWITCH TRANSACTION LOG</h1>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>$cell</td>";

    echo "</tr>\n";
//print_footer();                //show the moodle footer
}
//mysql_free_result($result);




?>