<?php

require "DBConnect.php";
$user = $_GET["user"];
$email = $_GET["email"];

if(trim($_GET['pswd'])=='' || trim($_GET['pswd2'])=='')
{
    echo('All fields are required!');
}
else if($_GET['pswd'] != $_GET['pswd2'])
{
    echo('Passwords do not match!');
}
else if (queryDB("SELECT name FROM users WHERE name='$user' ") != false)
{
    echo('Username already taken.');
}
else if (queryDB("SELECT email FROM users WHERE email='$email'") != false)
{
    echo('Email already taken.');
}
else
{
    $pswd = $_GET["pswd"];

    $sql = "insert into users(user_id, name, email, password) values (0, '" . $user . "', '" . $email . "', '" .
      $pswd . "')";

    echo modifyDB($sql) . "<br>Use back button to return";
}
?>