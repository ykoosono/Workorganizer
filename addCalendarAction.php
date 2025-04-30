<?php

require "DBConnect.php";

$title = $_GET["title"];
$month = $_GET["month"];
$year = $_GET["year"];
$day1 = $_GET["day1"];
$day2 = $_GET["day2"];
$day3 = $_GET["day3"];
$day4 = $_GET["day4"];
$day5 = $_GET["day5"];
$day6 = $_GET["day6"];
$day7 = $_GET["day7"];
$day8 = $_GET["day8"];
$day9 = $_GET["day9"];
$day10 = $_GET["day10"];
$day11 = $_GET["day11"];
$day12 = $_GET["day12"];
$day13 = $_GET["day13"];
$day14 = $_GET["day14"];
$day15 = $_GET["day15"];
$day16 = $_GET["day16"];
$day17 = $_GET["day17"];
$day18 = $_GET["day18"];
$day19 = $_GET["day19"];
$day20 = $_GET["day20"];
$day21 = $_GET["day21"];
$day22 = $_GET["day22"];
$day23 = $_GET["day23"];
$day24 = $_GET["day24"];
$day25 = $_GET["day25"];
$day26 = $_GET["day26"];
$day27 = $_GET["day27"];
$day28 = $_GET["day28"];
$day29 = $_GET["day29"];
$day30 = $_GET["day30"];
$day31 = $_GET["day31"];

//$userID = (int) $_SESSION['user_id'];
//$calendarID = queryDB("SELECT IDENT_CURRENT('calendar')");

$sql = "insert into calendar values (0, '" . $title . "', '" . $month . "', '" . $year . "', '" . $day1 . "', '" . $day2 . "', '" . $day3 
        . "', '" . $day4 . "', '" . $day5 . "', '" . $day6 . "', '" . $day7 . "', '" . $day8 . "', '" . $day9 . "', '". $day10 . "', '" . $day11 . "', '" . $day12 . "', '" . $day13 
        . "', '" . $day14 . "', '" . $day15 . "', '" . $day16 . "', '" . $day17 . "', '" . $day18 . "', '" . $day19 . "', '" . $day20 . "', '" . $day21 . "', '" . $day22 . "', '" 
        . $day23 . "', '" . $day24 . "', '" . $day25 . "', '" . $day26 . "', '" . $day27 . "', '" . $day28 . "', '" . $day29 . "', '" . $day30 . "', '" . $day31 . "')";

//to-do second sql statement for the users_calendars table to assign the many-to-many relationship between users and calendars
//$sql2 = "insert into users_calendars values (0, '" . $userID . "', '" . $calendarID . "' , '". 1 . "')";

echo modifyDB($sql) . "<br>Use back button to return";
//echo modifyDB($sql2)
?>

