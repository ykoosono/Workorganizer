<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: homepage.php");

    $userId = $_SESSION['user_id'];

    exit();
} else {
    header("Location: login.php");
    exit();
}
