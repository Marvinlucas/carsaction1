<?php
session_start();
require_once 'include/config.php';

$sender_id = $_GET['sender_id'];
$receiver_id = $_GET['receiver_id'];

$query = "SELECT * FROM chats WHERE (sender_id = '$sender_id' AND receiver_id = '$receiver_id') OR (sender_id = '$receiver_id' AND receiver_id = '$sender_id') ORDER BY timestamp";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$messages = array();

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
