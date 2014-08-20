<?php
/* in MySQLi

I need write
-. $_GET[actor]
instead of
-. $_GET["actor"]
or
-. $_GET['actor']

NOTE: IIS 7.5 (On Windows Server 2008 R2 Datacenter) with PHP Version 5.4.12 and mysqlnd 5.0.10
Version of MySQL 5.6.10

This code show a Movies with an actor ID_Actor
E.G. URL "http://127.0.0.1/test2.php?actor=14"
*/
?>
<?php
// CONNECT TO THE DATABASE
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = 'j@kenq2w3';
    $DB_NAME = 'webpay';
    
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

// A QUICK QUERY ON A FAKE USER TABLE

    $query = "SELECT DISTINCT Title FROM movie WHERE ID_movie IN ( SELECT DISTINCT ID_Movie FROM actor_scene WHERE ID_actor=$_GET[actor]) ";
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);

// GOING THROUGH THE DATA
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo stripslashes($row['Title'])."<br>";
            echo " ";
        }
    }
    else {
        echo 'NO RESULTS';
    }
// CLOSE CONNECTION
    mysqli_close($mysqli);
?>
<?php
/*
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+02:00";
 -- -- Database: `webpay` --  --
 --------------------------------------------------------  
 -- -- Table structure for table `enrol_webpay` -- --
DROP TABLE IF EXISTS `enrol_webpay`;
CREATE TABLE IF NOT EXISTS `enrol_webpay` (   `id` bigint(10) NOT NULL DEFAULT '0',   `business` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `receiver_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `receiver_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `courseid` bigint(10) NOT NULL DEFAULT '0',   `userid` bigint(10) NOT NULL DEFAULT '0',   `instanceid` bigint(10) NOT NULL DEFAULT '0',   `memo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `tax` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `option_name1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `option_selection1_x` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `option_name2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `option_selection2_x` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `payment_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `pending_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `reason_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `txn_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `parent_txn_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `payment_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',   `timeupdated` bigint(10) NOT NULL DEFAULT '0' ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
COMMIT;
*/
?>