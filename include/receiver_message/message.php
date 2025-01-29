<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';
// Check if the user is logged in
var_dump($_SESSION); 

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
    $user_id = $_SESSION["userid"]; // Assuming you've set this during authentication
} else {
    // User is not logged in, redirect to the login page
    header("Location: login_dashboard.php");
    exit();
}


if (isset($_GET['seller_id'])) {
    $_SESSION['seller_id'] = $_GET['seller_id'];
} else {
    // Handle the case where seller_id is not provided
    // For example, you could redirect the user or display an error message
    header("Location: error.php");
     exit();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>CarSaction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <link rel="stylesheet" type="text/css" href="message.css">
</head>

<body>
    
       <div class="chat-container">
    <div class="chat-header">
        <div class="profile-info">
            <img src="receiver_profile_picture.jpg" alt="Receiver Profile Picture" class="profile-picture">
            <span class="receiver-username">Receiver Username</span>
        </div>
    </div>
    <div class="chat-messages" id="chat-messages"></div>
    <div class="message-input-container">
        <input type="text" id="message-input" placeholder="Type your message...">
        <button id="send-button">Send</button>
    </div>
</div>
    
    
    <script>
    	$(document).ready(function() {
    const chatMessages = $('#chat-messages');
    const messageInput = $('#message-input');
    const sendButton = $('#send-button');

    const seller_id = <?php echo isset($_SESSION['seller_id']) ? $_SESSION['seller_id'] : 'null'; ?>;
   const user_id = <?php echo isset($user_id) ? $user_id : 'null'; ?>; // Using the user_id from the session
 //Assuming you have the user_id in the session



   function fetchMessages() {
    $.ajax({
        url: 'getMessage.php',
        type: 'GET',
        data: { sender_id: user_id, receiver_id: seller_id },
        dataType: 'json',
        success: function(response) {
            chatMessages.empty();

            // Check if the response is an empty array
            if (response.length === 0) {
                chatMessages.append($('<div>').text("No messages available."));
                return;
            }
            
            response.forEach(function(message) {
                const messageDiv = $('<div>').text(message.message);
                if (message.sender_id === user_id) {
                    messageDiv.addClass('sender-message');
                } else {
                    messageDiv.addClass('receiver-message');
                }
                chatMessages.append(messageDiv);
            });

            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        },
        error: function(xhr, status, error) {
            console.log(error); // Log any errors to the console
            }
        });
    }
// ... other code ...


    fetchMessages();

    sendButton.click(function() {
        const message = messageInput.val().trim();
        if (message !== '') {
            $.ajax({
                url: 'sendMessage.php',
                type: 'POST',
                data: { sender_id: user_id, receiver_id: seller_id, message: message },
                success: function() {
                    messageInput.val('');
                    fetchMessages();
                },
                error: function(xhr, status, error) {
                    console.log(error); // Log any errors to the console
                }

                
            });
        }


    });
});


    </script>



    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <?php include('include/footer.php'); ?>
</body>

</html>