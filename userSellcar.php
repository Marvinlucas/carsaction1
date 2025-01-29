<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $carMake = mysqli_real_escape_string($conn, $_POST["make"]);
    $carModel = mysqli_real_escape_string($conn, $_POST["model"]);
    $carYear = mysqli_real_escape_string($conn, $_POST["year"]);
    $carPrice = mysqli_real_escape_string($conn, $_POST["price"]);
    $old_price = mysqli_real_escape_string($conn, $_POST["old_price"]);
    $downpayment = mysqli_real_escape_string($conn, $_POST["downpayment"]);
    $monthlyPayment = mysqli_real_escape_string($conn, $_POST["monthly_payment"]);
    $interest_rate = mysqli_real_escape_string($conn, $_POST["interest_rate"]);
    $loan_term = mysqli_real_escape_string($conn, $_POST["loan_term"]);
    $carMileage = mysqli_real_escape_string($conn, $_POST["mileage"]);
      $fuel_type = mysqli_real_escape_string($conn, $_POST["fuel_type"]);
    $plateNumberEnding = mysqli_real_escape_string($conn, $_POST["plate_number_ending"]);
    $carColor = mysqli_real_escape_string($conn, $_POST["color"]);
    $carTransmission = mysqli_real_escape_string($conn, $_POST["transmission"]);
    $carDescription = mysqli_real_escape_string($conn, $_POST["description"]);
    $carOwner = mysqli_real_escape_string($conn, $_POST["owner"]);
    $carTitle = mysqli_real_escape_string($conn, $_POST["title"]);
     $carCondition = mysqli_real_escape_string($conn, $_POST["condition_car"]);
     $registration_expiry_date = mysqli_real_escape_string($conn, $_POST["registration_expiry_date"]);
      $carPapers = mysqli_real_escape_string($conn, $_POST["papers"]);
       $sale_information = mysqli_real_escape_string($conn, $_POST["sale_information"]);
    $carPostedDate = date("Y-m-d");
    $location = mysqli_real_escape_string($conn, $_POST['location']); // Sanitize location

    // Get the seller ID from the session
    $userId = $_SESSION["username"]; // Assuming you are using the username as the identifier

    // Check if the seller ID exists in the sellers table
    $query = "SELECT user_id FROM user WHERE username = '$userId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Fetch the user ID
        $row = mysqli_fetch_assoc($result);
        $userId = $row["user_id"];

        // Insert car data into the database
$sql = "INSERT INTO sellcars (make, model, year, old_price, price, downpayment, monthly_payment, mileage, plate_number_ending, color, transmission, description, owner, title, sellers_id, location, condition_car, papers, sale_information, fuel_type, registration_expiry_date, interest_rate, loan_term, posted_date) 
        VALUES ('$carMake', '$carModel', '$carYear', '$old_price', '$carPrice', '$downpayment', '$monthlyPayment', '$carMileage', '$plateNumberEnding', '$carColor', '$carTransmission', '$carDescription', '$carOwner', '$carTitle', '$userId', '$location', '$carCondition', '$carPapers', '$sale_information', '$fuel_type', '$registration_expiry_date', '$interest_rate', '$loan_term', NOW())";


        if (mysqli_query($conn, $sql)) {
            $carId = mysqli_insert_id($conn); // Get the ID of the newly inserted car record

            // Upload images
            $targetDir = "uploaded_car/"; // Directory to store the uploaded images
            $allowTypes = array('jpg', 'jpeg', 'png'); // Allowed image file types
            $uploadedImages = array(); // Array to store the uploaded image filenames

            // Check if the directory doesn't exist, create it
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            // Set directory permissions (make sure it's writable)
            if (!is_writable($targetDir)) {
                chmod($targetDir, 0755);
            }

            // Loop through each uploaded image
            foreach ($_FILES['images']['name'] as $key => $imageName) {
                $tempFile = $_FILES['images']['tmp_name'][$key];
                $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

                // Generate a unique filename for each image
                $targetFile = uniqid() . '.' . $imageFileType;

                // Check if the file type is allowed
                if (in_array($imageFileType, $allowTypes)) {
                    // Upload the file to the target directory
                    if (move_uploaded_file($tempFile, $targetDir . $targetFile)) {
                        $uploadedImages[] = $targetFile; // Store the uploaded image filename
                    }
                }
            }

            $imageColumns = ['image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8', 'image9', 'image10'];
            foreach ($uploadedImages as $key => $image) {
                if ($key < count($imageColumns)) {
                    $columnName = $imageColumns[$key];
                    $imagePath = mysqli_real_escape_string($conn, $image);
                    $updateSql = "UPDATE sellcars SET $columnName = '$imagePath' WHERE car_id = $carId";
                    mysqli_query($conn, $updateSql);
                }
            }
            // Car added successfully, redirect to the home page
            header("Location: userIndex.php");
            exit();
        } else {
            // Error occurred while adding car, display error message or redirect to an error page
            $errorMessage = "Error: " . mysqli_error($conn);
            // You can choose to display the error message or redirect to an error page
            // Display error message
            echo $errorMessage;

            // Or redirect to an error page
            // header("Location: error.php?message=" . urlencode($errorMessage));
            // exit();
        }
    } else {
        // Invalid seller ID, display error message or redirect to an error page
        $errorMessage = "Invalid seller ID.";
        // You can choose to display the error message or redirect to an error page
        // Display error message
        echo $errorMessage;

        // Or redirect to an error page
        // header("Location: error.php?message=" . urlencode($errorMessage));
        // exit();
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Sell Car</title>
    <!-- Add Bootstrap CSS -->
  
    <style>
    .image-input {
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <?php include('include/top_bar.php'); ?>
    <div class="container">
    <h1>Sell Car</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="make">Brand:</label>
                    <select id="car-brands" class="form-control" name="make" required>
                        <option value="Audi">Audi</option>
                        <option value="Bmw">BMW</option>
                        <option value="Chevrolet">Chevrolet</option>
                        <option value="Ford">Ford</option>
                        <option value="Honda">Honda</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Mercedes">Mercedes-Benz</option>
                        <option value="Nissan">Nissan</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Isuzu">Isuzu</option>
                        <option value="Kia">Kia</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Subaru">Subaru</option>
                        <option value="Volkswagen">Volkswagen</option>
                        <option value="Mitsubishi">Mitsubishi</option>
                        <option value="Mazda">Mazda</option>
                        <option value="Lexus">Lexus</option>
                        <option value="Peugeot">Peugeot</option>
                        <option value="Jaguar">Jaguar</option>
                        <option value="Jeep">Jeep</option>
                        <option value="Volvo">Volvo</option>
                        <option value="Land Rover">Land Rover</option>
                        <option value="Mini">Mini</option>
                        <option value="Porsche">Porsche</option>
                        <option value="Dodge">Dodge</option>
                        <option value="Chrysler">Chrysler</option>
                        <option value="Fiat">Fiat</option>
                        <option value="Alfa Romeo">Alfa Romeo</option>
                        <option value="Haval">Haval</option>
                        <option value="Great Wall Motors">Great Wall Motors</option>
                        <option value="BYD">BYD</option>
                        <option value="Geely">Geely</option>
                        <option value="MG (Morris Garages)">MG (Morris Garages)</option>
                        <option value="Changan">Changan</option>
                        <option value="Maxus">Maxus</option>
                        <option value="Foton">Foton</option>
                        <option value="SsangYong">SsangYong</option>
                        <option value="Tata Motors">Tata Motors</option>
                        <option value="JAC Motors">JAC Motors</option>
                        <option value="Dongfeng">Dongfeng</option>
                        <option value="Lamborgini">Lamborgini</option>
                        <!-- Add more options for other car brands -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" class="form-control" id="model" name="model" required>
                </div>

                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="text" class="form-control" id="year" name="year" required>
                </div>

                  <div class="form-group">
                    <label for="price">Old Price:</label>
                    <input type="number" class="form-control" id="old_price" name="old_price" onkeyup="formatPrice(this)" step="0.01" min="0.00" max="99999999999.99" required>
                </div>


                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" onkeyup="formatPrice(this)" step="0.01" min="0.00" max="99999999999.99" required>
                </div>


                <div class="form-group">
                    <label for="price">Downpayment:</label>
                    <input type="number" class="form-control" id="downpayment" name="downpayment" onkeyup="formatPrice(this)" step="0.01" min="0.00" max="999999999.99">
                </div>


                <div class="form-group">
                    <label for="price">Monthly payment:</label>
                    <input type="number" class="form-control" id="monthly_payment" name="monthly_payment" onkeyup="formatPrice(this)" step="0.01" min="0.00" max="999999999.99">
                </div>
              <div class="form-group">
                <label for="interestRate">Interest Rate (%):</label>
                <input type="number" class="form-control" id="interest_rate" name="interest_rate" step="0.01" min="0.00" max="9999.99">
              </div>
               <div class="form-group">
                <label for="loanTerm">Loan Term (months):</label>
                <input type="number" class="form-control" id="loan_term" name="loan_term" >
            </div>

                <a href="loancalculator.php" id="openLoanCalculator">Open Payment Calculator if Needed!</a>

                <div class="form-group">
                    <label for="mileage">Mileage:</label>
                    <input type="text" class="form-control" id="mileage" name="mileage" required>
                </div>

                <div class="form-group">
                    <label for="fuel_type">Fuel type:</label>
                    <select class="form-control" id="fuel_type" name="fuel_type" required>
                        <option value="Gasoline">Gasoline</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Electric">Electric</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="plate_number_ending">Plate Number Ending:</label>
                    <input type="text" class="form-control" id="plate_number_ending" name="plate_number_ending" required>
                </div>

                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="text" class="form-control" id="color" name="color" required>
                </div>

                <div class="form-group">
                    <label for="transmission">Transmission:</label>
                    <select class="form-control" id="transmission" name="transmission" required>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="owner">Owner:</label>
                    <select class="form-control" id="owner" name="owner" required>
                        <option value="1st Owner">1st Owner</option>
                        <option value="2nd Owner">2nd Owner</option>
                        <option value="3rd Owner">3rd Owner</option>
                        <option value="4rt Owner">4th Owner</option>
                        <option value="5th Owner">5th Owner</option>
                    </select>
                </div>

                <div class="form-group">
    <label for="condition">Condition of Car:</label>
    <select class="form-control" id="condition" name="condition_car" required>
        <option value="Unregistered">Unregistered</option>
        <option value="Registered">Registered</option>
    </select>
</div>

<!-- Add a CSS class to the registration_expiry_date field -->
<div class="form-group registration-expiry-date" style="display: none;">
    <label for="registration_expiry_date">Registration Expiry Date:</label>
    <input type="date" class="form-control" id="registration_expiry_date" name="registration_expiry_date">
</div>
                 <div class="form-group">
                    <label for="papers">Documents :</label>
                    <select class="form-control" id="papers" name="papers" required>
                        <option value="Complete OR/CR">Complete OR/CR</option>
                        <option value="Lost OR/CR">Lost OR/CR</option>
                          <option value="Lost Original OR/CR">Lost Original OR/CR</option>
                        <option value="Xerox Copy of OR/CR">Xerox Copy of OR/CR</option>
                        <option value="Lost OR, Available CR">Lost OR, Available CR</option>
                        <option value="Available OR, Lost CR">Available OR, Lost CR</option>
                    </select>
                </div>

                 <div class="form-group">
                    <label for="sale_information">Sale Information:</label>
                    <select class="form-control" id="sale_information" name="sale_information" required>
                        <option value="Open Deed of Sale">Open Deed of Sale</option>
                        <option value="Deed of Sale not Available">Deed of Sale not Available</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>


                </div>
            </div>
        </div>

       
<div class="form-group" id="image-container">
    <!-- No initial image field here -->
</div>
<button type="button" class="btn btn-secondary" id="add-image-btn">Add Images</button>

        <input type="submit" class="btn btn-primary" value="Sell Car">
    </form>
</div>


    <!-- Add Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
   <script>
document.addEventListener("DOMContentLoaded", function() {
    var imageContainer = document.getElementById("image-container");
    var maxImages = 10;
    var imageCount = 0;

    var addImageInput = function() {
        if (imageCount < maxImages) {
            imageCount++;
            var newImageInput = document.createElement("div");
            newImageInput.className = "form-group image-input";
            newImageInput.innerHTML = '<label for="image' + imageCount + '">Image ' + imageCount +
                ':</label>' +
                '<input type="file" class="form-control" id="image' + imageCount +
                '" name="images[]" required>' +
                '<button type="button" class="remove-image-btn"><i class="fas fa-times"></i></button>';
            imageContainer.appendChild(newImageInput);

            if (imageCount === maxImages) {
                document.getElementById("add-image-btn").disabled = true;
            }
        }
    };

    var removeImageInput = function(event) {
        var imageInput = event.target.parentNode;
        imageInput.parentNode.removeChild(imageInput);
        imageCount--;
        document.getElementById("add-image-btn").disabled = false;
    };

    var checkFileType = function(event) {
        var input = event.target;
        var file = input.files[0];

        if (file && !file.type.startsWith("image/")) {
            input.value = ""; // Clear the input value
            alert("Please select an image file.");
        }
    };

    document.getElementById("add-image-btn").addEventListener("click", addImageInput);

    imageContainer.addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-image-btn")) {
            removeImageInput(event);
        }
    });

    imageContainer.addEventListener("change", function(event) {
        if (event.target.type === "file") {
            checkFileType(event);
        }
    });
});
</script> 

<script>
document.addEventListener("DOMContentLoaded", function() {
    var conditionDropdown = document.getElementById("condition");
    var registrationExpiryDateField = document.querySelector(".registration-expiry-date");

    // Add an event listener to the condition dropdown
    conditionDropdown.addEventListener("change", function() {
        if (conditionDropdown.value === "Registered") {
            // Show the registration_expiry_date field when "Registered" is selected
            registrationExpiryDateField.style.display = "block";
        } else {
            // Hide the registration_expiry_date field for other options
            registrationExpiryDateField.style.display = "none";
        }
    });
});
</script>



    <?php include('include/footer.php'); ?>
</body>

</html>