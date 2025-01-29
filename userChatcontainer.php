<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Ensure the user is logged in and get their user_id
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page or handle the case where the user is not logged in.
    // You can replace this with the appropriate logic.
    header("Location: login.php");
    exit();
}

// Initialize sender_id in the session if it's not set
if (!isset($_SESSION["sender_id"])) {
    // Fetch the ID of the first user in the user table (you may want to change this logic)
    $userName = mysqli_query($conn, "SELECT * FROM user LIMIT 1")
        or die("Failed to query database" .mysqli_error());
    $uName = mysqli_fetch_assoc($userName);
    $_SESSION["sender_id"] = $uName['user_id'];
}

// Determine the sender's name and ID based on the URL or session
if (isset($_GET["sellers_id"])) {
    $seller_id = $_GET["sellers_id"];
} else {
    $seller_id = $_SESSION["sender_id"];
}

$userName = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '".$seller_id."' ")
    or die("Failed to query database" .mysqli_error());
$uName = mysqli_fetch_assoc($userName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Chatbox</title>
    <!-- Include Bootstrap CSS -->
   
</head>
<body>
  <?php include('include/top_bar.php'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <input type="text" value='<?php echo $seller_id; ?>' id="sender_id" hidden/>
                        <?php echo $uName["username"]; ?>
                    </h4>
                </div>
                <div class="card-body" id="msgBody" style="max-height: 650px; overflow-y: scroll; overflow-x: hidden;">
                    <?php 
                    // Fetch chat messages based on sender_id and receiver_id
                    $chats = mysqli_query($conn, "SELECT * FROM chat WHERE (receiver_id = '".$_SESSION["user_id"]."' AND sender_id = '".$seller_id."') OR (receiver_id = '".$seller_id."' AND sender_id = '".$_SESSION["user_id"]."')")
                        or die("Failed to query database" .mysqli_error());

                    while($chat = mysqli_fetch_assoc($chats)) {
                        if($chat["receiver_id"] == $_SESSION["user_id"])
                            echo "<div style='text-align:right;'>
                            <p style='background-color:lightgray; word-wrap:break-word; display:inline-block; padding:5px; border-radius:10px; max-width:70%;'>
                                ".$chat["message"]."
                            </p>
                            </div>";
                        else
                            echo "<div style='text-align:left;'>
                            <p style='background-color:lightblue; word-wrap:break-word; display:inline-block; padding:5px; border-radius:10px; max-width:70%;'>
                                ".$chat["message"]."
                            </p>
                            </div>";
                    }
                    ?>
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <textarea id="message" class="form-control" style="height:70px;"></textarea>
                        <div class="input-group-append">
                            <button id="send" class="btn btn-primary" style="height: 70%;">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#send").on("click", function(){
            $.ajax({
                url:"insertMessage.php",
                method:"POST",
                data:{
                    receiver_id: $("#sender_id").val(),
                    sender_id: <?php echo $_SESSION["user_id"]; ?>,
                    message: $("#message").val(),
                },
                dataType:"text", 
                success:function(data)
                {
                    $("#message").val("");
                }
            });
        });

        setInterval(function () {
            $.ajax({
                url: "realTimeChat.php",
                method: "POST",
                data: {
                    receiver_id: $("#sender_id").val(),
                    sender_id: <?php echo $_SESSION["user_id"]; ?>,
                },
                dataType: "text",
                success: function (data) {
                    $("#msgBody").html(data);
                }
            });
        }, 700);
    });
</script>

 <?php include('include/footer.php'); ?>
</body>
</html>
