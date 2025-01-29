
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
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Service</title>
    <!-- Bootstrap CSS link -->
  
</head>
<body>
  <?php include('include/navbar_users.php'); ?>

    <!-- Container for the form -->
    <div class="container mt-4">
        <h2>Contact Us</h2>
        <p>If you have any questions or concerns, please fill out the form below.</p>
        <?php

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve user input
        $name = $_POST["name"];
        $email = $_POST["email"];
        $message = $_POST["message"];

        // Create a database connection (modify the credentials accordingly)
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Check if the connection was successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare the SQL INSERT statement
        $sql = "INSERT INTO customer_service (name, email, message) VALUES (?, ?, ?)";

        // Create a prepared statement
        $stmt = mysqli_stmt_init($conn);

        // Check if the prepared statement was created successfully
        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind the parameters to the statement
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Data inserted successfully
                echo "Message sent successfully!";
            } else {
                // Error occurred while executing the statement
                echo "Error: " . mysqli_error($conn);
            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);
        } else {
            // Error occurred while preparing the statement
            echo "Error: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    }
    ?>

        <!-- Contact Form -->
        <form method="POST" action="customerService.php"> <!-- Specify the action attribute -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" name="message" id="message" rows="4" placeholder="Your Message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js scripts (required for Bootstrap functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
