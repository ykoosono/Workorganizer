<?php
// Database connection
$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT id, title, date AS start, details AS description FROM events WHERE calendar_id = :calendar_id");
    $stmt->bindParam(':calendar_id', $_GET['calendar_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Fetch events
    $events = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $events[] = $row;
    }

    // Output events as JSON
    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
