<?php
// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=workorganizer_db;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$calendarId = $_GET['calendar_id'] ?? null;
if (!$calendarId) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT event_id, title, date, is_complete FROM events WHERE calendar_id = ?");
$stmt->execute([$calendarId]);
$events = [];

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $events[] = [
        'id' => $row['event_id'],
        'title' => $row['title'],
        'start' => $row['date'],
        'color' => $row['is_complete'] ? '#28a745' : '#ffc107'  // green for complete, amber for incomplete
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
