<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];

    // Fetch user data
    $stmt = $conn->prepare("SELECT firstname, lastname, username, email, profile_picture FROM users WHERE username = ?");
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
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
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
        // Update the password in the database
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $username);
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

    // Update other fields in the database
    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, profile_picture = ? WHERE username = ?");
    $stmt->bind_param("ssss", $_POST["firstname"], $_POST["lastname"], $profilePicture, $username);
    $stmt->execute();

    // Check if any other fields were updated
    if ($stmt->affected_rows === 1) {
        $updatedFields[] = "firstname"; // Add "firstname" to the updated fields
        $updatedFields[] = "lastname"; // Add "lastname" to the updated fields
    }

    // Close statement
    $stmt->close();

    // Update the session firstname and lastname
    $_SESSION["firstname"] = $_POST["firstname"];
    $_SESSION["lastname"] = $_POST["lastname"];

    // You may need to handle the profile picture separately if it's uploaded

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
<?php include('include/top_bar_admin.php'); ?>
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

    <form id="updateForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>
