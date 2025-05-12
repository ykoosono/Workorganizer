<?php

require "DBConnect.php";
session_start();

$calendarID = (int) $_SESSION['calendar_id'];

$title = $_GET["title"];
$month = $_GET["month"];
$year = $_GET["year"];
$desc = $_GET["desc"];


$sql = "UPDATE calendars SET  title='" . $title . "', description='" . $desc . "', month='" . $month . "', year='" . $year . "'  WHERE id='" . $calendarID . "'";

echo modifyDB($sql);
header("Location: homepage.php");

?>

