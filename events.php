<?php
global $conn;
session_start();
include 'db.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'load') {
    $query = "SELECT title,e.id As event_id, u.id as user_id,start_event, end_event,name FROM events e JOIN user u ON e.affected_to = u.id  WHERE u.id = " .$_SESSION['id']  ." ;";
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin')
        $query = "SELECT title,e.id As event_id, u.id as user_id,start_event, end_event,name FROM events e JOIN user u ON e.affected_to = u.id;";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
            $data[] = [
                'id' => $row['event_id'],
                'title' => $row['title'] ."--" .$row['name'],
                'start' => $row['start_event'],
                'end' => $row['end_event'],
                'affected_to' => $row['name']
            ];
        } else {
            $data[] = [
                'id' => $row['event_id'],
                'title' => $row['title'],
                'start' => $row['start_event'],
                'end' => $row['end_event'],
                'affected_to' => $row['name']
            ];
        }

    }
    echo json_encode($data);
}

if ($action == 'insert') {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $affected_to = $_POST['affected_to'];
    $query = "INSERT INTO events (title, start_event, end_event,affected_to	) VALUES ('$title', '$start', '$end', '$affected_to')";
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
