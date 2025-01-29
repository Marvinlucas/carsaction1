<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Add Bootstrap CSS link here -->
</head>
<body>
    <?php include('include/top_bar_rev.php'); ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Forgot Password</h2>
                    </div>
                    <?php 
                       
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Validate email (you can add more validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } else {
        // Check if the email exists in your 'user' table
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Generate a unique token (you can use a library for this)
            $token = bin2hex(random_bytes(32));

            // Store the token and email in a 'password_reset' table
            $sql = "INSERT INTO password_reset (email, token, created_at)
                    VALUES ('$email', '$token', NOW())";
            if ($conn->query($sql) === TRUE) {
                // Send a password reset link to the user's email using PHPMailer
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'carsaction1@gmail.com'; // Your Gmail email address
                $mail->Password = 'xthb nuvq ctcb yhql'; // Your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('carsaction1@gmail.com', 'CARsaction');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset';
                $mail->Body = 'Click the following link to reset your password: <a href="http://localhost/carsaction1/resetPassword.php?token=' . $token . '">Reset Password</a>';
                
                if ($mail->send()) {
                    echo '<div class="alert alert-success" role="alert">Password reset link sent to your email.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert"> Error sending email:  . $mail->ErrorInfo </div>';
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">  Email not found in our database.</div>';
        }
    }

    // Close the database connection
    $conn->close();
}
                    ?>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery links at the end of the body -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <?php include('include/footer.php'); ?>
</body>
</html>

