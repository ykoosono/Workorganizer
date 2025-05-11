<?php
session_start();

$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';
$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $event_id = $_POST['event_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $details = $_POST['details'] ?? '';
    $assigned_user_id = $_POST['assigned_user_id'] ??  '';

    $update_query_event = "UPDATE events SET date = ?, details = ?, title = ? WHERE id = ?";
    $stmt = $pdo->prepare($update_query_event);
    $stmt->execute([$date, $details, $title, $event_id]);

    $update_query_user = "UPDATE task_assignments SET user_id = ? WHERE event_id = ?";
    $stmt = $pdo->prepare($update_query_user);
    $stmt->execute([$assigned_user_id, $event_id]);

    header('Location: view_calendar.php?id='.$event_id);

}

?>
