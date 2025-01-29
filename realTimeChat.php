<?php
session_start();
require_once 'include/config.php';

$output = "";

if (isset($_POST["receiver_id"]) && isset($_POST["sender_id"])) {
    $fromUser = $_POST["receiver_id"];
    $toUser = $_POST["sender_id"];

    $chats = mysqli_query($conn, "SELECT * FROM chat WHERE (receiver_id = '$fromUser' AND sender_id = '$toUser') OR (receiver_id = '$toUser' AND sender_id = '$fromUser')")
        or die("Failed to query database" . mysqli_error($conn));

    while ($chat = mysqli_fetch_assoc($chats)) {
        if ($chat["receiver_id"] == $fromUser)
            $output .= "<div style='text-align:right;'>
	         <p style='background-color:lightgray; word-wrap:break-word; display:inline-block; padding:5px; border-radius:10px; max-width:70%;'>
                    " . $chat["message"] . "
	         </p>
	          </div>";
        else
            $output .= "<div style='text-align:left;'>
	         <p style='background-color:lightblue; word-wrap:break-word; display:inline-block; padding:5px; border-radius:10px; max-width:70%;'>
                    " . $chat["message"] . "
	         </p>
	        </div>";
    }
} else {
    $output = "Invalid input data.";
}

echo $output;
?>
