<?php
require_once 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calendar_id = $_POST['calendar_id'];
    $event_id = $_POST['event_id'];
    $assigned_users = $_POST['assigned_users'] ?? [];

    if ($event_id && !empty($assigned_users)) {
        // Insert task assignments into the database
        $stmt = $pdo->prepare("INSERT INTO event_assignments (event_id, user_id) VALUES (?, ?)");
        foreach ($assigned_users as $user_id) {
            $stmt->execute([$event_id, $user_id]);
        }
        echo "<div class='container mt-5'><div class='alert alert-success'>Tasks assigned successfully.</div></div>";
    } else {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Please select an event and at least one user.</div></div>";
    }
}
?>
