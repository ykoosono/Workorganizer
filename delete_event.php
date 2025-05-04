<?php
include 'db_connection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM events WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);

    echo 'success';
} else {
    echo 'error';
}
?>
