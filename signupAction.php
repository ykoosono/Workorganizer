<?php

require "DBConnect.php";


$user = $_GET["user"];
$pswd = $_GET["pswd"];
$email = $_GET["email"];

$sql = "insert into users values (0, '" . $user . "', '" . $email . "', '" .
  $pswd . "', '" . "a" . "')";

echo modifyDB($sql) . "<br>Use back button to return";

?>