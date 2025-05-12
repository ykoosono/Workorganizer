<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'], $_POST['is_complete'])) {
    $eventId = intval($_POST['event_id']);
    $isComplete = intval($_POST['is_complete']);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=workorganizer_db;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Critical: Make sure the query targets a specific task only!
        $stmt = $pdo->prepare("UPDATE events SET is_complete = ? WHERE event_id = ?");
        $stmt->execute([$isComplete, $eventId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
