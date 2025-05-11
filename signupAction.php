<?php

require "DBConnect.php";
$user = $_GET["user"];
$email = $_GET["email"];

$userResult = queryDB('SELECT * FROM users WHERE name="'.$user.'"');
$emailResult = queryDB('SELECT email FROM users WHERE email="'.$email.'"');

if(trim($_GET['pswd'])=='' || trim($_GET['pswd2'])=='')
{
    echo('All fields are required!');
}
else if($_GET['pswd'] != $_GET['pswd2'])
{
    echo('Passwords do not match!');
}
else if (mysqli_num_rows($userResult) > 0)
{
    echo('Username already taken.');
}
else if (mysqli_num_rows($emailResult) > 0)
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