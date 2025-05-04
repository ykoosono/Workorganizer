<?php
include 'db_connection.php'; // Ensure this connects to your DB with $pdo

$query = "SELECT id, title, start_task, end_task FROM tasks";
$stmt = $pdo->prepare($query);
$stmt->execute();

$tasks = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tasks[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start_task'],
        'end' => $row['end_task']
    ];
}

echo json_encode($tasks);
?>
