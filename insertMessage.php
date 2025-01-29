<?php
session_start();
require_once 'include/config.php';

if (isset($_POST["receiver_id"]) && isset($_POST["message"])) {
    $receiver_id = $_POST["receiver_id"];
    $message = $_POST["message"];
    $sender_id = $_SESSION["user_id"]; // Use the ID of the logged-in user as sender_id

    $output = "";

    $sql = "INSERT INTO chat (receiver_id, sender_id, message) VALUES ('$receiver_id', '$sender_id', '$message')";

    if ($conn->query($sql)) {
        $output .= "Message sent successfully.";
    } else {
        $output .= "Error. Please Try Again.";
    }
} else {
    $output = "Invalid input data.";
}

echo $output;
?>
