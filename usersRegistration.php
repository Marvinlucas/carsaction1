<?php
require_once 'include/config.php';
require_once 'include/head.php';

// Initialize the verification flag
$verificationCompleted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the input data
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $phone_number = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $password = $_POST["password"]; // We'll validate and hash it later
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $complete_address = filter_var($_POST["barangay"], FILTER_SANITIZE_STRING);

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT email FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists, display an error message
        $registration_error = "The email already exists.";
        $emailFieldClass = "is-invalid"; // Add a CSS class for styling
    } else {

    // Validate the password
    if (strlen($password) < 8) {
        $registration_error = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $registration_error = "Password must contain at least one uppercase letter, one lowercase letter, and one digit.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Process file uploads
        $target_dir = "upload_ID_face/";
        $id_picture = uniqid() . '_' . basename($_FILES["id_picture"]["name"]);
        $selfie_picture = uniqid() . '_' . basename($_FILES["selfie_picture"]["name"]);
        $id_target_file = $target_dir . $id_picture;
        $selfie_target_file = $target_dir . $selfie_picture;

        if (move_uploaded_file($_FILES["id_picture"]["tmp_name"], $id_target_file) &&
            move_uploaded_file($_FILES["selfie_picture"]["tmp_name"], $selfie_target_file)) {
            // Insert the user data into the database using prepared statements
            $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, username, phone_number, password, email, complete_address, id_picture, selfie_picture, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $status = "pending"; // Assuming status should be set to "pending"

            $stmt->bind_param("ssssssssss", $firstname, $lastname, $username, $phone_number, $hashed_password, $email, $complete_address, $id_picture, $selfie_picture, $status);

            if ($stmt->execute()) {
                $_SESSION['registration_success'] = true; // Set the session variable
                $stmt->close();
                header("Refresh: 3; URL=userLogin.php"); // Redirect to login.php after 5 seconds
                exit();
            } else {
                $registration_error = "Error: " . $stmt->error;
                $stmt->close();
            }
        } else {
            $registration_error = "Error uploading files.";
        }
    }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

     <?php include('include/navbar_rev.php'); ?>

      <?php if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) : ?>
                    <p>Registration successful! You'll be redirected to the login page shortly...</p>
                <?php elseif (isset($registration_error)) : ?>
                    <p>Registration was not successful. <?php echo $registration_error; ?></p>
                <?php endif; ?>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="card-title">Buyer Registration</h1>
                    <a href="javascript:history.back()" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
               <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" name="phone" id="phone" class="form-control" pattern="[0-9]{11}" required>
                    </div>

                     
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span id="password-strength" class="text-muted"></span>
                </div>
                  <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control <?php echo isset($emailFieldClass) ? $emailFieldClass : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="Address">Complete Address:</label>
                        <input type="text" name="barangay"  id="complete_address" class="form-control" required>
                    </div>
                  
           <h5>For Authorization, please upload a photo of your Face and ID</h5>
            <div class="form-group">
                <label for="id_picture">ID Picture:</label>
                <input type="file" class="form-control" id="id_picture" name="id_picture" accept="image/*" required>
            </div>
           <img id="idImage" src="#" alt="ID Picture" style="max-width: 150px;">
            <div class="form-group">
                <label for="selfie_picture">Selfie Picture:</label>
                <input type="file" class="form-control" id="selfie_picture" name="selfie_picture" accept="image/*" required>
            </div>

          <img id="selfieImage" src="#" alt="Selfie Picture" style="max-width: 150px;">
    
    </div>
    <div class="form-group" style="background-color: yellowgreen; color: black; border-radius: 5%; margin-left: 10px; margin-right: 10px;">
    <label for="agreeTerms">Disclaimer:</label>
    <p>After you register your account, it requires administrative approval, and you will need to wait for 12-24 hours for your account to be approved.</p>
</div>

          <div class="form-group">
    <div class="row">
        <div class="col-sm-1">
            <input type="checkbox" name="agreeTerms" id="agreeTerms" required style="margin-left: 5px;">
        </div>
        <div class="col-sm-11">
            <label for="agreeTerms">
                <span class="text-primary">I agree to the Terms and Conditions:</span>
                By clicking the "I agree to the Terms and Conditions" checkbox and submitting my personal information, I acknowledge and consent to the collection, storage, and processing of my data in accordance with the Terms and Conditions outlined by this platform. I understand that this information is necessary for the registration process and that it will be handled securely and responsibly. Additionally, I recognize that my account may require administrative approval, and I am willing to wait for the specified time frame for my registration to be processed. My agreement signifies that I have read and understood the platform's policies regarding the use of personal data and that I willingly comply with them.
            </label>
        </div>
    </div>
</div>

                      <button type="submit" class="btn btn-primary" id="verifyButton">Register</button>
                </form>
              
            </div>
        </div>
    </div>

      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- Scripts (similar to your implementation) -->
    <!-- ... -->
    <script>
document.getElementById('id_picture').addEventListener('change', function(e) {
    var idImage = document.getElementById('idImage');
    idImage.src = URL.createObjectURL(e.target.files[0]);
});

document.getElementById('selfie_picture').addEventListener('change', function(e) {
    var selfieImage = document.getElementById('selfieImage');
    selfieImage.src = URL.createObjectURL(e.target.files[0]);
});
</script>
<script>
    function readImage(input, targetId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + targetId).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#id_picture').change(function() {
        readImage(this, 'idImage');
    });

    $('#selfie_picture').change(function() {
        readImage(this, 'selfieImage');
    });
</script>
      <script>
        // Function to check password strength
        function checkPasswordStrength() {
            var password = document.getElementById('password').value;
            var strength = 0;

            // Check for minimum password length
            if (password.length >= 8) {
                strength += 1;
            }

            // Check for uppercase letters
            if (/[A-Z]/.test(password)) {
                strength += 1;
            }

            // Check for lowercase letters
            if (/[a-z]/.test(password)) {
                strength += 1;
            }

            // Check for at least one digit
            if (/[0-9]/.test(password)) {
                strength += 1;
            }

            // Display password strength message
            var strengthText = "";
            switch (strength) {
                case 1:
                    strengthText = "Weak";
                    break;
                case 2:
                    strengthText = "Moderate";
                    break;
                case 3:
                    strengthText = "Strong";
                    break;
                case 4:
                    strengthText = "Very Strong";
                    break;
                default:
                    strengthText = "";
            }

            var passwordStrengthElement = document.getElementById('password-strength');
            passwordStrengthElement.textContent = strengthText;
        }

        // Add an event listener to the password input field
        document.getElementById('password').addEventListener('input', checkPasswordStrength);
    </script>
      <?php include('include/footer.php'); ?>
</body>
</html>
