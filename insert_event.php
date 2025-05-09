<?php
include 'workorganizer_db';

if (isset($_POST['title'], $_POST['start'], $_POST['end'])) {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $query = "INSERT INTO events (title, start_event, end_event) VALUES (:title, :start, :end)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':title' => $title,
        ':start' => $start,
        ':end' => $end
    ]);

    echo 'success';
} else {
    echo 'error';
}
?>
