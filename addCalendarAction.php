<?php
require "DBConnect.php";
session_start();

$title = $_GET["title"];
$desc = $_GET["desc"];

$sql = "INSERT INTO calendars (title, description, month, year) VALUES ('" . $title . "', '" . $desc . "')";

$sql2 = "SELECT LAST_INSERT_ID() AS calendarID";

$result = modifyReturnIDDB($sql, $sql2);
$row = $result->fetch_assoc();
$calendarID = $row['calendarID'];

$userID = (int) $_SESSION['user_id'];

$sql2 = "insert into users_calendars values (0, '" . $calendarID . "' , '". $userID . "', 1)";
echo modifyDB($sql2);
header("Location: homepage.php");
?>

