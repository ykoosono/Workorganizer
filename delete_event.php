<?php
include 'db.php'; // Make sure this file sets up the $pdo connection properly

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $query = "DELETE FROM events WHERE event_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);

    echo 'success';
} else {
    echo 'error';
}
?>
