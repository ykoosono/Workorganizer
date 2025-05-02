<?php
require "DBConnect.php";
session_start();

$title = $_GET["title"];
$month = $_GET["month"];
$year = $_GET["year"];
$desc = $_GET["desc"];

$sql = "INSERT INTO calendar (title, description, month, year) VALUES ('" . $title . "', '" . $desc . "', '" . $month . "', '" . $year . "') RETURNING id";
        //. "SELECT LAST_INSERT_ID();";

$calendarID = queryDB($sql);
echo $calendarID . "<br>Use back button to return";

$sql2 = "select LAST_INSERT_ID();";
//$calendarID = (int) queryDB($sql2);
//echo $calendarID;

$userID = (int) $_SESSION['user_id'];

//to-do second sql statement for the users_calendars table to assign the many-to-many relationship between users and calendars
$sql2 = "insert into users_calendars values (0, '" . $calendarID . "' , '". $userID . "', 1)";
echo modifyDB($sql2);
?>

