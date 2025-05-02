<?php
$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $eventId = $_POST['event_id'] ?? null;
  $isComplete = $_POST['is_complete'] ?? null;

  if ($eventId && is_numeric($isComplete)) {
    $stmt = $pdo->prepare("UPDATE events SET is_complete = ? WHERE id = ?");
    $stmt->execute([$isComplete, $eventId]);
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
  }

} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
