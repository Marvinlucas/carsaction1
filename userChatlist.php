<?php
session_start();
require_once 'include/config.php'; // Include your database connection logic
require_once 'include/head.php'; 



// Check if the user is logged in as a buyer
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or display an error message
    header("Location: userLogin.php"); // Replace 'login.php' with your actual login page
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="userChatList.css">
  <style>
    .chat-list-container {
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 20px;
      margin-top: 20px;
    }
    .username-link {
      cursor: pointer;
    }
        /* Add CSS to create circular profile pictures */
    .profile-picture {
      width: 50px;
       height: 50px;
        border-radius: 50%;
         object-fit: cover;
      
      transition: transform 0.3s; /* Add a smooth transition for the zoom effect */
    }
    /* Add hover effect to zoom in on the image */
    .profile-picture:hover {
      transform: scale(3.2); /* Adjust the scale factor to control the zoom level */
    }
    .separator-line {
  width: 100%;
  height: 1px;
  background-color: #ccc;
  margin: 10px 0; /* Adjust the margin as needed */
}
  </style>
  <title>Chat List</title>
</head>
<body>

<?php include('include/top_bar.php'); ?>



  <header class="text-center mt-4">
    <h1>Chat List</h1>
  </header>

  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="chat-list-container">
          <div class="chat-list">
<?php

// Get the user's ID from the session
$userId = $_SESSION['user_id'];

// Fetch chat list with sender's and receiver's information and their most recent messages
$query = "SELECT 
            CASE
                WHEN m.sender_id = $userId THEN m.receiver_id
                ELSE m.sender_id
            END AS chat_partner_id,
            MAX(m.timestamp) AS max_timestamp,
            MAX(CASE WHEN m.sender_id = $userId THEN m.message ELSE NULL END) AS sent_message,
            MAX(CASE WHEN m.sender_id != $userId THEN m.message ELSE NULL END) AS received_message,
            MAX(CASE WHEN m.sender_id != $userId THEN s.firstname ELSE 
                     CASE WHEN m.sender_id = $userId THEN r.firstname ELSE NULL END
                END) AS chat_partner_firstname,
            MAX(CASE WHEN m.sender_id != $userId THEN s.lastname ELSE 
                     CASE WHEN m.sender_id = $userId THEN r.lastname ELSE NULL END
                END) AS chat_partner_lastname,
            MAX(CASE WHEN m.sender_id != $userId THEN s.username ELSE 
                     CASE WHEN m.sender_id = $userId THEN r.username ELSE NULL END
                END) AS chat_partner_username,
            MAX(CASE WHEN m.sender_id != $userId THEN s.profile_picture ELSE 
                     CASE WHEN m.sender_id = $userId THEN r.profile_picture ELSE NULL END
                END) AS chat_partner_profile_picture
          FROM chat m
          LEFT JOIN user s ON 
            CASE
                WHEN m.sender_id = $userId THEN m.receiver_id
                ELSE m.sender_id
            END = s.user_id
          LEFT JOIN user r ON m.receiver_id = r.user_id
          WHERE m.sender_id = $userId OR m.receiver_id = $userId
          GROUP BY chat_partner_id
          ORDER BY max_timestamp DESC";

$result = mysqli_query($conn, $query);
// Define the default profile picture path and filename
$defaultProfilePicture = 'include/image/noprofile.jpg';

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Inside the while loop that iterates through chat partners
while ($row = mysqli_fetch_assoc($result)) {
    $chatPartnerId = $row['chat_partner_id'];
    $chatPartnerFirstName = $row['chat_partner_firstname']; // Replace with the actual column name
    $chatPartnerLastName = $row['chat_partner_lastname'];   // Replace with the actual column name
    $chatPartnerProfilePicture = $row['chat_partner_profile_picture'];
    $mostRecentMessage = $row['received_message'] ?: $row['sent_message'];

    // Check if the chat partner has a profile picture, otherwise use the default
    if (empty($chatPartnerProfilePicture) || !file_exists($chatPartnerProfilePicture)) {
        $chatPartnerProfilePicture = $defaultProfilePicture;
    }

    // Concatenate first name and last name to create display name
    $chatPartnerDisplayName = $chatPartnerFirstName . ' ' . $chatPartnerLastName;

    echo '<div class="chat-item">';
    echo '<a style="color: black;" href="userChatcontainer.php?sellers_id=' . $chatPartnerId . '" class="username-link">';
    echo '<div class="user-info">';
    echo '<img src="' . $chatPartnerProfilePicture . '" alt="No Profile Picture" class="profile-picture">';
    echo '<h5 class="username">' . $chatPartnerDisplayName . '</h5>'; // Display the chat partner's display name

    // Display the most recent message for this chat
    echo '<p class="message">' . $mostRecentMessage . '</p>';

    echo '</div>';
    echo '</a>';
    echo '</div>';

    echo '<div class="separator-line"></div>';
}




?>


            <!-- Add more chat items here -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

 <?php include('include/footer.php'); ?>
</body>
</html>
