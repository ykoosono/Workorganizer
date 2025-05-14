<?php

require "DBConnect.php";
session_start();

$calendarID = (int) $_SESSION['calendar_id'];

$title = $_GET["title"];
$desc = $_GET["desc"];


$sql = "UPDATE calendars SET  title='" . $title . "', description='" . $desc . "'  WHERE id='" . $calendarID . "'";

echo modifyDB($sql);
header("Location: homepage.php");

?>

