<?php
include 'workorganizer_db';

if (isset($_POST['id'], $_POST['title'], $_POST['start'], $_POST['end'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $query = "UPDATE events SET title = :title, start_event = :start, end_event = :end WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id' => $id,
        ':title' => $title,
        ':start' => $start,
        ':end' => $end
    ]);

    echo 'success';
} else {
    echo 'error';
}
?>
