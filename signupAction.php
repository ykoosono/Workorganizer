<?php

require "DBConnect.php";

$user = $_GET["user"];
$pswd = $_GET["pswd"];
$email = $_GET["email"];

$sql = "insert into user values (0, '" . $user . "', '" . $pswd . "', '" .
  $email . "')";

echo modifyDB($sql) . "<br>Use back button to return";

?>