<?php
// Include database connection
require_once 'workorganizer_db';

header('Content-Type: application/json');

// Fetch events from the database
$stmt = $pdo->prepare("SELECT id, title, date AS start, details FROM events WHERE calendar_id = ? AND is_complete = 0 ORDER BY date ASC");
$stmt->execute([$_GET['id']]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return events as JSON
echo json_encode($events);
?>

