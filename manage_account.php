<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];

    // Fetch user data
    $stmt = $conn->prepare("SELECT firstname, lastname, username, email, profile_picture FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the data
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $profilePicture = $row['profile_picture'];
    } else {
        // Handle the case when user data is not found
        // You can redirect to an error page or display a message
        echo "User data not found.";
        exit();
    }

    // Close statement
    $stmt->close();
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newUsername = $_POST["username"];

    // TODO: Validate and process the form data

    $updatedFields = array(); // Store the fields that were updated successfully

    // Check if the new username is different from the current username
    if ($newUsername !== $username) {
        // Update the username in the database
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE user SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $newUsername, $username);
        $stmt->execute();

        // Check if the username was updated
        if ($stmt->affected_rows === 1) {
            $_SESSION["username"] = $newUsername;
            $updatedFields[] = "username"; // Add "username" to the updated fields
        }

        // Close statement
        $stmt->close();
    }

    // Check if the new password and confirm password match and if the new password is provided
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];
    
    if ($newPassword !== "" && $newPassword === $confirmPassword) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update the password in the database
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashedPassword, $username);
        $stmt->execute();

        // Check if the password was updated
        if ($stmt->affected_rows === 1) {
            $updatedFields[] = "password"; // Add "password" to the updated fields
        }

        // Close statement
        $stmt->close();
    } elseif ($newPassword !== $confirmPassword) {
        // Password validation failed, display an error message
        $errorMessage = "Password does not match the confirm password.";
        $_SESSION["updateMessage"] = $errorMessage;
    }
  
    $targetDir = "profile_pic_uploads/";
    $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]); 

    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK) {
        // Check if the file upload was successful
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            $profilePicture = $targetFile; // Get the file name
            
            // Update the profile picture column in the database
            $stmt = $conn->prepare("UPDATE user SET profile_picture = ? WHERE username = ?");
            $stmt->bind_param("ss", $profilePicture, $username);
            $stmt->execute();

            // Check if the profile picture was updated
            if ($stmt->affected_rows === 1) {
                $updatedFields[] = "profile picture"; // Add "profile picture" to the updated fields
            } else {
                // Handle the case where the database update failed
                $errorMessage = "Profile picture update failed.";
                $_SESSION["updateMessage"] = $errorMessage;
            }

            // Close statement
            $stmt->close();
        } else {
            // Handle the case where file upload failed
            $errorMessage = "Profile picture upload failed.";
            $_SESSION["updateMessage"] = $errorMessage;
        }
    } elseif ($_FILES["profile_picture"]["error"] !== UPLOAD_ERR_NO_FILE) {
        // Handle the case where file upload encountered an error
        $errorMessage = "Profile picture upload error: " . $_FILES["profile_picture"]["error"];
        $_SESSION["updateMessage"] = $errorMessage;
    }

    // Update the firstname and lastname fields
    $newFirstname = $_POST["firstname"];
    $newLastname = $_POST["lastname"];

    if ($newFirstname !== $firstname || $newLastname !== $lastname) {
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE user SET firstname = ?, lastname = ? WHERE username = ?");
        $stmt->bind_param("sss", $newFirstname, $newLastname, $username);
        $stmt->execute();

        // Check if both firstname and lastname were updated
        if ($stmt->affected_rows === 2) {
            $updatedFields[] = "firstname"; // Add "firstname" to the updated fields
            $updatedFields[] = "lastname";  // Add "lastname" to the updated fields
        } else {
            // Handle the case where the database update failed
            $errorMessage = "Firstname and/or lastname update failed.";
            $_SESSION["updateMessage"] = $errorMessage;
        }

        // Close statement
        $stmt->close();
    }

    // Check if any fields were updated successfully
    if (!empty($updatedFields)) {
        $_SESSION["updateMessage"] = "Updated successfully: " . implode(", ", $updatedFields);
    } else {
        $_SESSION["updateMessage"] = "No fields were updated.";
    }

    // Redirect to the same page to avoid resubmission
    header("Location: manage_account.php");
    exit();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Account</title>
    
</head>
<body>
<?php include('include/top_bar.php'); ?>
<div class="container">
    <h1 class="mt-4">Manage Account</h1>

    <?php if (isset($_SESSION["updateMessage"])) : ?>
        <div class="mt-4">
            <p><?php echo $_SESSION["updateMessage"]; ?></p>
        </div>
        <?php unset($_SESSION["updateMessage"]); ?>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)) : ?>
        <div class="mt-4 text-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form id="updateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <!-- Rest of the form -->
        <div class="form-group">
            <label for="firstname">First Name:</label>
            <input type="text" class="form-control" id="firstname" name="firstname"
                   value="<?php echo htmlspecialchars($firstname); ?>" required>
        </div>

        <div class="form-group">
            <label for="lastname">Last Name:</label>
            <input type="text" class="form-control" id="lastname" name="lastname"
                   value="<?php echo htmlspecialchars($lastname); ?>" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username"
                   value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
            <?php if ($profilePicture): ?>
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="mt-2" style="max-width: 150px;">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
<?php include('include/footer.php'); ?>
</body>
</html>
