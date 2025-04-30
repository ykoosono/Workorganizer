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

//populate fields with info to be edited from database


$userID;
$calendarID;

$sql = "UPDATE calendar SET  title='" . $title . "', month='" . $month . "', year='" . $year . "', 1st='" . $day1 . "', 2nd='" . $day2 . "', 3rd='" . $day3 
        . "', 4th='" . $day4 . "', 5th='" . $day5 . "', 6th='" . $day6 . "', 7th='" . $day7 . "', 8th='" . $day8 . "', 9th='" . $day9 . "', 10th='". $day10 . "', 11th='" . $day11 . "', 12th='" . $day12 . "', 13th='" . $day13 
        . "', 14th='" . $day14 . "', 15th='" . $day15 . "', 16th='" . $day16 . "', 17th='" . $day17 . "', 18th='" . $day18 . "', 19th='" . $day19 . "', 20th='" . $day20 . "', 21th='" . $day21 . "', 22nd='" . $day22 . "', 23rd='" 
        . $day23 . "', 24th='" . $day24 . "', 25th='" . $day25 . "', 26th='" . $day26 . "', 27th='" . $day27 . "', 28th='" . $day28 . "', 29th='" . $day29 . "', 30th='" . $day30 . "', 31st='" . $day31 . "' WHERE id='" . $userID . "'";

echo modifyDB($sql) . "<br>Use back button to return";

?>

