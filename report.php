<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $reportingUserId = $_SESSION["username"];
} else {
    header("Location: login.php");
    exit();
}

$confirmationMessage = $errorMessage = "";
$reportedUsers = [];

// Check if the car ID is available and set it in the session
if (isset($_GET["car_id"])) {
    $_SESSION["reported_car_id"] = $_GET["car_id"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["reported_username"]) && isset($_POST["report_message"]) && !empty($_POST["report_message"])) {
        $reportedUsername = $_POST["reported_username"];
        $reportMessage = $_POST["report_message"];

        // Process selected checkboxes
        $selectedReasons = isset($_POST["report_reason"]) ? $_POST["report_reason"] : [];
        $reasonsString = implode(", ", $selectedReasons);

        // Check if reported_car_id is available in the session
        if (isset($_SESSION["reported_car_id"])) {
            $reportedCarId = $_SESSION["reported_car_id"];
            // Clear the reported_car_id from the session after using it
            unset($_SESSION["reported_car_id"]);
        } else {
            $reportedCarId = null; // No reported_car_id provided
        }


        // Handle file upload
        $reportImageFileName = null;
        if (isset($_FILES["report_image"]) && $_FILES["report_image"]["error"] === UPLOAD_ERR_OK) {
            // Specify the target directory
            $targetDirectory = "report_image/";

            // Create the directory if it doesn't exist
            if (!file_exists($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }
                 // Generate a unique file name to avoid overwriting
            $reportImageFileName = uniqid() . "_" . basename($_FILES["report_image"]["name"]);

            // Specify the target file path
            $targetFilePath = $targetDirectory . $reportImageFileName;

            // Check if the file was successfully moved to the target directory
            if (move_uploaded_file($_FILES["report_image"]["tmp_name"], $targetFilePath)) {
                // File was uploaded successfully
            } else {
                $errorMessage = "Error uploading the image.";
            }
        }

       $sql = "INSERT INTO report_users (reported_username, report_message, report_reasons, reported_car_id, report_image) VALUES (?, ?, ?, ?,?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Replace "sssi" with the correct types based on your data types in the database
    $stmt->bind_param("sssis", $reportedUsername, $reportMessage, $reasonsString, $reportedCarId, $reportImageFileName);
    if ($stmt->execute()) {
        $confirmationMessage = "Thank you for reporting. The admin will review the message.";
    } else {
        $errorMessage = "Error reporting message: " . $stmt->error;
    }
    $stmt->close();
} else {
    $errorMessage = "Error preparing statement: " . $conn->error;
}
}
}


$sql = "SELECT c.*, ru.report_message, c.car_id
        FROM report_users ru
        JOIN sellcars c ON c.car_id = ru.reported_car_id
         LEFT JOIN user u ON u.username = ru.reported_username";

         



$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reportedUsers[] = $row;
    }
} else {
    $errorMessage = "Error fetching reported users: " . $conn->error;

}

?>



<!DOCTYPE html>
<html>

<head>
    <title>CarSaction</title>
   
</head>

<body>
<?php include('include/top_bar.php'); ?>


    <div class="container mt-4">
        <?php if ($confirmationMessage !== ""): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($confirmationMessage); ?></div>
        <?php endif; ?>

        <?php if ($errorMessage !== ""): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <h2>Report Seller</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="reported_username" value="<?php echo htmlspecialchars($reportingUserId); ?>">
            <div class="form-group">
                <label for="report_reason">Why do you want to report the seller?</label><br>
                <input type="checkbox" name="report_reason[]" value="false_information"> False Information<br>
                <input type="checkbox" name="report_reason[]" value="fake_account"> Fake Account<br>
                <input type="checkbox" name="report_reason[]" value="threatening_message"> They sent threatening message<br>
                <input type="checkbox" name="report_reason[]" value="abusive_language"> They used abusive or vulgar language<br>
                <input type="checkbox" name="report_reason[]" value="false_contact_info"> The seller gave false contact information<br>
            </div>
            <div class="form-group">
                <label for="report_message">Message to Admin:</label>
                <textarea class="form-control" id="report_message" name="report_message" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="report_image">Upload Image (optional):</label>
                <input type="file" class="form-control-file" id="report_image" name="report_image">
            </div>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
    </div>



    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <?php include('include/footer.php'); ?>
</body>

</html>