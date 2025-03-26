<?php

require "DBConnect.php";

if(trim($_GET['pswd'])=='' || trim($_GET['pswd2'])=='')
{
    echo('All fields are required!');
}
else if($_GET['pswd'] != $_GET['pswd2'])
{
    echo('Passwords do not match!');
}
else
{
    $user = $_GET["user"];
    $pswd = $_GET["pswd"];
    $email = $_GET["email"];

    $sql = "insert into users(user_id, name, email, password) values (0, '" . $user . "', '" . $email . "', '" .
      $pswd . "')";

    echo modifyDB($sql) . "<br>Use back button to return";
}
?>