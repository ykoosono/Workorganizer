<?php
include 'workorganizer_db';

$query = "SELECT id, title, start_event, end_event FROM events";
$stmt = $pdo->prepare($query);
$stmt->execute();

$events = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start_event'],
        'end' => $row['end_event']
    ];
}

echo json_encode($events);
?>
