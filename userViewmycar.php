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
?>
<!DOCTYPE html>
<html>

<head>
    <title>Car Listing</title>
  
</head>

<body>
    <?php include('include/top_bar.php'); ?>

    <div class="container">
        <?php
        // Check if the car ID is provided in the URL
        if (isset($_GET['car_id'])) {
            $carId = $_GET['car_id'];

            // Retrieve car details from the database using prepared statement
            $sql = "SELECT sellcars.car_id, sellcars.title, sellcars.posted_date, sellcars.make, sellcars.model, sellcars.year, sellcars.price, sellcars.downpayment, sellcars.monthly_payment, sellcars.description, sellcars.mileage, sellcars.plate_number_ending, sellcars.color, sellcars.transmission, sellcars.owner, user.user_id AS sellers_id, user.firstname, user.lastname, sellcars.location, sellcars.condition_car, sellcars.papers, sellcars.sale_information, sellcars.fuel_type, sellcars.registration_expiry_date, sellcars.old_price
                    FROM sellcars
                    INNER JOIN user ON sellcars.sellers_id = user.user_id
                    WHERE sellcars.car_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $carId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if the query execution was successful
            if ($result) {
                // Check if the car exists
                if (mysqli_num_rows($result) > 0) {
                    // Fetch the car details
                    $row = mysqli_fetch_assoc($result);

                    $carMake = $row['make'];
                    $carModel = $row['model'];
                    $carYear = $row['year'];
                    $carPrice = $row['price'];
                    $downpayment = floatval($row['downpayment']);
                    $monthlyPayment = floatval($row['monthly_payment']);
                    $carDescription = $row['description'];
                    $carMileage = $row['mileage'];
                    $owner = $row['owner'];
                    $carPlate = $row['plate_number_ending'];
                    $carColor = $row['color'];
                    $carTransmission = $row['transmission'];
                    $carTitle = $row['title'];
                    $carPostedDate = $row['posted_date'];
                    $sellerFirstName = $row['firstname'];
                    $sellerLastName = $row['lastname'];
                    $sellerLocation = $row['location'];
                     $carCondition = $row['condition_car'];
                    $carPapers = $row['papers'];
                    $sale_information = $row['sale_information'];
                    $fuel_type = $row['fuel_type'];
                    $registration_expiry_date = $row['registration_expiry_date'];
                     $old_price = $row['old_price'];

                    echo "<h1 class='card-title' style='color: #007bff;'>$carTitle</h1>"; // Add the header title with styling
                    echo "<p>Posted on <span style='color: #007bff;'>" . date("F d, Y", strtotime($carPostedDate)) . "</span></p>";

                    // Retrieve car photos from the database
                   // Retrieve car photos from the database
$photosSql = "SELECT image1, image2, image3, image4, image5, image6, image7, image8, image9, image10
            FROM sellcars
            WHERE car_id = ?";
$stmt = mysqli_prepare($conn, $photosSql);
mysqli_stmt_bind_param($stmt, "i", $carId);
mysqli_stmt_execute($stmt);
$photosResult = mysqli_stmt_get_result($stmt);

                 // Check if the query execution was successful
if ($photosResult && mysqli_num_rows($photosResult) > 0) {
    echo "<div id='carouselExampleIndicators' class='carousel slide' data-ride='carousel'>";
    echo "<div class='carousel-inner'>";

    $firstImage = true;

    // Loop through the photos
    while ($photoRow = mysqli_fetch_assoc($photosResult)) {
        for ($i = 1; $i <= 10; $i++) {
            $photoUrl = $photoRow['image'.$i];

            // Check if the photo URL is not empty and the file exists
            if (!empty($photoUrl) && file_exists("uploaded_car/$photoUrl")) {
                if ($firstImage) {
                    echo "<div class='carousel-item active'>";
                    $firstImage = false;
                } else {
                    echo "<div class='carousel-item'>";
                }
                echo "<img src='uploaded_car/$photoUrl' class='d-block w-100' alt='Car Image'>";
                echo "</div>";
            }
        }
    }

    echo "</div>";

    // Add the carousel indicators
    echo "<ol class='carousel-indicators'>";
    mysqli_data_seek($photosResult, 0); // Reset the result pointer
    $firstIndicator = true;

    $indicatorIndex = 0;
    while ($photoRow = mysqli_fetch_assoc($photosResult)) {
        for ($i = 1; $i <= 10; $i++) {
            $photoUrl = $photoRow['image'.$i];

            // Check if the photo URL is not empty and the file exists
            if (!empty($photoUrl) && file_exists("uploaded_car/$photoUrl")) {
                $indicatorClass = $firstIndicator ? 'active' : '';
                echo "<li data-target='#carouselExampleIndicators' data-slide-to='$indicatorIndex' class='$indicatorClass'></li>";
                $firstIndicator = false;
                $indicatorIndex++;
            }
        }
    }

    echo "</ol>";

    echo "</div>";

    // Display the thumbnail images
    echo "<div class='row mt-4 justify-content-center'>"; // Add 'justify-content-center' class to center the thumbnails
    echo "<div class='button-container d-flex align-items-center justify-content-between'>"; // Add the button container div and align-items-center, justify-content-between classes

    echo "<button class='btn btn-primary' id='prevButton'><i class='fa fa-chevron-left'></i></button>";

    echo "<div class='thumbnail-container d-flex align-items-center'>"; // Add the thumbnail container div and align-items-center class

    mysqli_data_seek($photosResult, 0); // Reset the result pointer
    $thumbnailIndex = 0;
    $thumbnailLimit = 3; // Maximum number of thumbnails to show at a time
    $thumbnailCount = 0; // Counter for displayed thumbnails

    $thumbnailBatch = array(); // Array to store thumbnail URLs

    while ($photoRow = mysqli_fetch_assoc($photosResult)) {
        for ($i = 1; $i <= 10; $i++) {
            $photoUrl = $photoRow['image'.$i];

            // Check if the photo URL is not empty and the file exists
            if (!empty($photoUrl) && file_exists("uploaded_car/$photoUrl")) {
                $thumbnailBatch[] = $photoUrl; // Add thumbnail URL to the batch array

                if ($thumbnailCount < $thumbnailLimit) {
                    echo "<div class='col-md-4 text-center' style='margin: 0;'>"; // Apply inline style to remove margin
                    echo "<img src='uploaded_car/$photoUrl' class='img-fluid thumbnail thumbnail-image' alt='Thumbnail Image' data-target='#carouselExampleIndicators' data-slide-to='$thumbnailIndex'>";
                    echo "</div>";
                    $thumbnailCount++;
                }
                $thumbnailIndex++;
            }
        }
    }

    echo "</div>"; // Close the thumbnail container div

    echo "<button class='btn btn-primary' id='nextButton'><i class='fa fa-chevron-right'></i></button>";

    echo "</div>"; // Close the button container div

    echo "</div>"; // Close the main row div

    echo "<style>";
    echo ".thumbnail-image {";
    echo "    width: 100px;"; // Set the width to the desired value
    echo "    height: 100px;"; // Set the height to the desired value
    echo "    object-fit: cover;"; // Maintain aspect ratio and cover the container
    echo "}";
    echo ".active-thumbnail {";
    echo "    border: 2px solid blue;";
    echo "}";
    echo "</style>";

    // JavaScript code for handling thumbnail navigation and active thumbnail
    echo "<script>";
    echo "var thumbnailBatch = " . json_encode($thumbnailBatch) . ";"; // Pass the thumbnail URLs to JavaScript
    echo "var currentThumbnailIndex = 0;";
    echo "var thumbnailLimit = $thumbnailLimit;";
    echo "var prevThumbnailBtn = document.getElementById('prevButton');";
    echo "var nextThumbnailBtn = document.getElementById('nextButton');";
    echo "prevThumbnailBtn.addEventListener('click', function() {";
    echo "    if (currentThumbnailIndex > 0) {";
    echo "        currentThumbnailIndex -= thumbnailLimit;";
    echo "        showThumbnails();";
    echo "    }";
    echo "});";
    echo "nextThumbnailBtn.addEventListener('click', function() {";
    echo "    if (currentThumbnailIndex + thumbnailLimit < thumbnailBatch.length) {";
    echo "        currentThumbnailIndex += thumbnailLimit;";
    echo "        showThumbnails();";
    echo "    }";
    echo "});";
    echo "function showThumbnails() {";
    echo "    var thumbnailContainer = document.querySelector('.thumbnail-container');";
    echo "    thumbnailContainer.innerHTML = '';";
    echo "    for (var i = currentThumbnailIndex; i < currentThumbnailIndex + thumbnailLimit; i++) {";
    echo "        if (thumbnailBatch[i]) {";
    echo "            var thumbnail = document.createElement('div');";
    echo "            thumbnail.className = 'col-md-4 text-center';"; // Modified class name
    echo "            var thumbnailImage = document.createElement('img');";
    echo "            thumbnailImage.src = 'uploaded_car/' + thumbnailBatch[i];";
    echo "            thumbnailImage.className = 'img-fluid thumbnail thumbnail-image';"; // Modified class name
    echo "            thumbnailImage.alt = 'Thumbnail Image';";
    echo "            thumbnailImage.setAttribute('data-target', '#carouselExampleIndicators');";
    echo "            thumbnailImage.setAttribute('data-slide-to', i);";
    echo "            if (i === currentThumbnailIndex) {";
    echo "                thumbnailImage.classList.add('active-thumbnail');"; // Add the active-thumbnail class to the current thumbnail
    echo "            }";
    echo "            thumbnail.appendChild(thumbnailImage);";
    echo "            thumbnailContainer.appendChild(thumbnail);";
    echo "        }";
    echo "    }";
    echo "}";
    echo "showThumbnails();"; // Initial display of thumbnails
    echo "</script>";
} else {
    // No photos available
    echo "<p>No photos available for this car.</p>";
    $noThumbnails = true; // Define $noThumbnails as true
}

                    
                } else {
                    // Car not found
                    echo "<p>Car not found.</p>";
                }
            } else {
                // Query execution failed, display error message
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            // Car ID is not provided, redirect to the car listing page
            header("Location: car_listing.php");
            exit();
        }
    

        // Display the detailed view of the car
        echo "<br>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h2 class='card-title' style='background-color: #f2f2f2; padding: 10px; border-radius: 5px;'>Price and Term</h2>"; // Add the header title with styling
       // Calculate the difference between the current price and the old price
$priceDifference = $old_price - $carPrice;

echo "<h2 class='card-text' style='padding: 10px;'> Price: <span style='margin-left: 60px; color: #007bff;'>₱ " . number_format($carPrice, 2, '.', ',') . "</span> <span style='margin-left: 60px; color: gray; text-decoration: line-through;'>₱ " . number_format($old_price, 2, '.', ',') . "</span></h2>";
echo "<p style='padding: 10px; color: black; font-size: 14px;'>Saved:<span style='margin-left: 60px; width:50px; background-color:gray; border-radius: 15px; color: #fff;'> ₱ " . number_format($priceDifference, 2, '.', ',') . "</p>";

        echo "<h2 class='card-text' style='padding: 10px;'>Downpayment: <span style='margin-left: 60px; color: #007bff;'>₱ " . number_format($downpayment, 2, '.', ',') . "</span></h2>";
        echo "<h2 class='card-text' style='padding: 10px;'>Monthly Payment: <span style='margin-left: 60px; color: #007bff;'>₱ " . number_format($monthlyPayment, 2, '.', ',') . "</span></h2>";
        echo "</div>";
        echo "</div>";
        echo "<br>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>";
        echo "<th colspan='2' style='border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2; border-radius: 5px;'>$carMake $carModel Specifications</th>"; // Add the header title with styling
        echo "</tr>";
        echo "<tr>";
          echo "<td style='border: 1px solid #ddd; padding: 10px; width: 50%;'><i class='fas fa-car'></i> Brand: <b>$carMake</b></td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px; width: 50%;'> <i class='fas fa-car'></i> Model: <b>$carModel</b></td>";
        echo "</tr>";
        echo "<tr>";
       echo "<td style='border: 1px solid #ddd; padding: 10px;'><i class='far fa-calendar-alt'></i> Year: <b>$carYear</b></td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'><i class='fas fa-user'></i> Number of Owner: <b>$owner</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='border: 1px solid #ddd; padding: 10px; width: 50%;'> <i class='fas fa-paint-brush'></i> Color: <b>$carColor</b></td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px; width: 50%;'><i class='fas fa-road'> Mileage: <b>$carMileage</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'> <i class='fas fa-cogs'></i> Transmission: <b>$carTransmission</b></td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'> <i class='fas fa-car'></i> Plate Number Ending: <b>$carPlate</b></td>";
        echo "</tr>";
          echo "<tr>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'> <i class='fas fa-gas-pump'></i> Fuel Type: <b>$fuel_type</b></td>";
       echo "<td style='border: 1px solid #ddd; padding: 10px;'><i class='fas fa-file'></i> Condition of Car: <b>$carCondition</b>";

if ($carCondition == 'Registered') {
    echo " until: <b>" . date("F d, Y", strtotime($registration_expiry_date)) ."</b>";
}

echo "</td>";
echo "</tr>";
         echo "<tr>";
          echo "<td style='border: 1px solid #ddd; padding: 10px;'> <i class='fas fa-newspaper'></i> Documents: <b>$carPapers</b></td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'> <i class='fas fa-newspaper'></i> Sale Information: <b>$sale_information</b></td>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "<br>";
         echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h2 class='card-title' style='background-color: #f2f2f2; padding: 10px; border-radius: 5px;'>Description</h2>"; // Add the header title with styling
        echo "<p class='card-text' style='padding: 10px;'>" . nl2br($carDescription) . "</p>"; // Add the original text from the textarea using nl2br()
        echo "</div>";
        echo "</div>";
        echo "<br>";
    
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h2 class='card-title' style='background-color: #f2f2f2; padding: 10px; border-radius: 5px;'>Seller Information</h2>"; // Add the header title with styling
        echo "<h1 class='card-text' style='font-size: 20px; color:#007bff'>$sellerFirstName $sellerLastName</h1> <br>";
        echo "<h3 class='card-text' style='font-size: 20px; color:#00000f'>Location:$sellerLocation</h3> <br>";


  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            echo "<div style='display: flex; justify-content: flex-end;'>";
            echo "<a href='userProfile.php? class='btn btn-primary'>Back to my profile</a>";
            echo "</div>";
        }
        ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                var thumbnailBatch = <?php echo json_encode($thumbnailBatch); ?>; // Get the thumbnail batch data

                var startIndex = 0; // Start index of the displayed thumbnails
                var thumbnailLimit = 3; // Maximum number of thumbnails to show at a time

                var prevButton = $('#prevButton');
                var nextButton = $('#nextButton');

                prevButton.on('click', function () {
                    startIndex -= thumbnailLimit;
                    if (startIndex < 0) {
                        startIndex = thumbnailBatch.length - thumbnailLimit;
                    }
                    displayThumbnails(startIndex);
                });

                nextButton.on('click', function () {
                    startIndex += thumbnailLimit;
                    if (startIndex >= thumbnailBatch.length) {
                        startIndex = 0;
                    }
                    displayThumbnails(startIndex);
                });

                function displayThumbnails(startIndex) {
                    var endIndex = startIndex + thumbnailLimit;
                    var thumbnails = '';

                    for (var i = startIndex; i < endIndex; i++) {
                        var index = i % thumbnailBatch.length;
                        var photoUrl = 'uploaded_car/' + thumbnailBatch[index];
                        var activeClass = (index === getActiveSlideIndex()) ? 'active-thumbnail' :
                            ''; // Add active-thumbnail class to the currently active thumbnail
                        var thumbnailImage = '<div class="col-md-4 text-center" style="margin: 0;">' +
                            '<img src="' + photoUrl + '" class="img-fluid thumbnail thumbnail-image ' + activeClass +
                            '" alt="Thumbnail Image" data-target="#carouselExampleIndicators" data-slide-to="' + index +
                            '">' +
                            '</div>';
                        thumbnails += thumbnailImage;
                    }

                    $('.thumbnail-container .col-md-4.text-center').remove(); // Remove existing thumbnails
                    $('.thumbnail-container').prepend(thumbnails); // Prepend the new set of thumbnails

                    // Update active thumbnail event handler
                    $('.thumbnail-image').on('click', function () {
                        $('.thumbnail-image').removeClass('active-thumbnail');
                        $(this).addClass('active-thumbnail');
                    });
                }

                // Display the initial set of thumbnails
                displayThumbnails(startIndex);

                // Listen for the slid.bs.carousel event to update the active-thumbnail class
                $('#carouselExampleIndicators').on('slid.bs.carousel', function () {
                    updateActiveThumbnail();
                });

                // Function to update the active-thumbnail class based on the current slide
                function updateActiveThumbnail() {
                    var activeSlideIndex = getActiveSlideIndex();
                    $('.thumbnail-image').removeClass('active-thumbnail');
                    $('.thumbnail-image[data-slide-to="' + activeSlideIndex + '"]').addClass('active-thumbnail');
                }

                // Function to get the index of the currently active slide
                function getActiveSlideIndex() {
                    return $('#carouselExampleIndicators .carousel-item.active').index();
                }
            });
            <?php if (!$noThumbnails) { ?>
    displayThumbnails(0);
<?php } ?>
        </script>
  <?php include('include/footer.php'); ?>
</body>

</html>
