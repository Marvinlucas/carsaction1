<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the 'sellers_id' is provided in the URL
if (isset($_GET['sellers_id'])) {
    $sellersId = $_GET['sellers_id'];

    // Include your database connection code here (e.g., connect.php)

    // Fetch user profile data based on the provided sellers_id
    $query = "SELECT * FROM user WHERE user_id = $sellersId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $profile_picture =  $row['profile_picture'];
        $username = $row['username'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $phone_number = $row['phone_number'];
        $email = $row['email'];
        $complete_address = $row['complete_address'];

        // Fetch cars listed by the specified user, including sold cars
       $carQuery = "SELECT car_id, image1, make, model, year, price, sold, sold_price FROM sellcars WHERE sellers_id = $sellersId";

        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $carQuery .= " AND (make LIKE '%$search%' OR model LIKE '%$search%' OR year LIKE '%$search%' OR price LIKE '%$search%')";
        }

        $carQuery .= " ORDER BY posted_date DESC"; // Add this line to order by posted_date in descending order

        $carResult = mysqli_query($conn, $carQuery);
    } else {
        echo "Error: Unable to fetch user profile." . mysqli_error($conn);
    }
} else {
    // Redirect to an error page or handle the case where 'sellers_id' is not provided in the URL
    header("Location: error.php"); // Replace 'error.php' with the actual error page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Include Bootstrap CSS here -->

    <!-- Include your custom CSS here -->
    <link rel="stylesheet" href="styles.css">
    <style>
        .img-fluid {
            max-width: 400px; /* Adjust the width as needed */
            max-height: 250px; /* Adjust the height as needed */
            border-radius: 50%; /* Create a circular shape by setting border-radius to 50% */
            display: block; /* Center the image */
            margin: 0 auto; /* Center the image horizontally */
        }
        .col-md-8 {
            font-size: 25px;
        }
        /* Custom CSS for card elements */
        .card.car-listing {
            margin-bottom: 20px;
        }
        .card-body {
            height: 150px; /* Adjust the height as needed */
        }
        .card-text p {
            margin: 0; /* Remove default margin */
        }
        .card-img-top{
            max-height: 112px;
        }
        /* Additional styles for sold cars */
        .sold-card {
            border: 2px solid #ff0000; /* Red border for sold cars */
            opacity: 0.7; /* Reduced opacity for sold cars */
        }
    </style>
</head>
<body>
    <?php include('include/top_bar.php'); ?>
    <div class="container mt-5">
        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-4">
               <img src="<?php echo (file_exists($profile_picture) && !empty($profile_picture)) ? $profile_picture : 'include/image/noprofile.jpg'; ?>" alt="Profile Picture" class="img-fluid" style="width: 170px; height: 170px; border-radius: 50%; object-fit: cover;  border: 3px solid gray;"><br><br>
                <div class="container">
                    <div class="col-md-12" style="font-size: 20px;">
                        <p><strong>Name:</strong><br> <?php echo $firstname . ' ' . $lastname; ?></p>
                        <p><strong>Phone Number:</strong><br> <?php echo $phone_number; ?></p>
                        <p><strong>Email:</strong><br> <a style="color:  #007bff;"><?php echo $email; ?></a></p>
                        <p><strong>Address:</strong><br> <?php echo $complete_address; ?></p>
                    </div>
                </div>
            </div>

        <div class="col-md-8">
    <h2 class="text-center">Listed Cars</h2><br>
    <?php
    // Define the default image path and filename
$defaultImage = 'include/image/noimage.jpg';
    $count = 0; // Initialize a counter
    while ($row = mysqli_fetch_assoc($carResult)) {
        if ($count % 3 === 0) {
            // Start a new row for card bodies after every 3 cards
            echo '<div class="row">';
        }

        $carId = $row['car_id'];
        $carMake = $row['make'];
        $carModel = $row['model'];
        $carYear = $row['year'];
        $carPrice = $row['price'];
        $sold = $row['sold'];
        $soldPrice = $row['sold_price']; // Get the sold_price
         // Check if the car has an image available
    $carCover = 'uploaded_car/' . $row['image1'];
    if (empty($row['image1']) || !file_exists($carCover)) {
        $carCover = $defaultImage; // Use the default image
    }

         $cardClass = $sold == 1 ? 'sold-card' : ''; // Add a class for sold cars

                echo "<div class='col-sm-12 col-md-6 col-lg-4'>";
                echo "<div class='card car-listing $cardClass'>";

                if ($sold == 1) {
                    echo "<div class='card-link'>";
                } else {
                    echo "<a href='userViewmycar.php?car_id=$carId' class='card-link'>";
                }

          echo "<img src='$carCover' class='card-img-top' alt='No Image Available'>";
                echo "<div class='card-body'>";
                echo "<h2 class='card-title' style='color: black; font-size: 18px;'>$carMake $carModel</h2>";
                echo "<div class='card-text' style='color: black; font-size: 15px;'>";
                echo "<p><b>Year:</b> $carYear</p>";
                echo "<p><b>Price:</b> <span style='color: #007bff;'>₱ " . number_format($carPrice, 2, '.', ',') . "</span></p>";


                if ($sold == 1) {
                     echo "<p style='color: red; font-size:16px;'>Sold for ₱ " . number_format($soldPrice, 2, '.', ',') . "</p>";
                } else {
                    //echo "<p><b>Status:</b> Available</p>";
                }

                echo "</div>";
                echo "</div>";

                if ($sold == 1) {
                    echo "</div>";
                } else {
                    echo "</a>";
                }

                echo "</div>";
                echo "</div>";

                $count++;

                if ($count % 3 === 0) {
                    echo '</div>';
                }
            }

            if ($count % 3 !== 0) {
                echo '</div>';
            }
            ?>

</div>
            </div>
        </div>
    </div>
              
    <!-- Include Bootstrap and JavaScript scripts here -->
    <!-- Bootstrap JS (popper.js and jquery.js) are recommended to include -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
