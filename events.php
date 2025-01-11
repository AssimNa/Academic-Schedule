<?php
global $conn;
include 'db.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'load') {
    $query = "SELECT * FROM events ORDER BY id";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'start' => $row['start_event'],
            'end' => $row['end_event']
        ];
    }
    echo json_encode($data);
}

if ($action == 'insert') {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $query = "INSERT INTO events (title, start_event, end_event) VALUES ('$title', '$start', '$end')";
    $conn->query($query);
    echo 'Event Inserted';
}

if ($action == 'update') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $query = "UPDATE events SET title='$title', start_event='$start', end_event='$end' WHERE id='$id'";
    $conn->query($query);
    echo 'Event Updated';
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $query = "DELETE FROM events WHERE id='$id'";
    $conn->query($query);
    echo 'Event Deleted';
}
?>
