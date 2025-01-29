<?php
session_start();
require_once 'include/config.php';
// I<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Insert the message into the database
    $query = "INSERT INTO chats (sender_id, receiver_id, message, timestamp) VALUES ('$sender_id', '$receiver_id', '$message', NOW())";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
