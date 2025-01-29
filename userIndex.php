<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: userLogin.php");
    exit();
}

// Fetch available cars from the database
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>


  <!DOCTYPE html>
<html>

<head>
    <title>Car Listing</title>
    
   <style>
    /* Adjust styles for desktop view */
    @media (min-width: 992px) {
        /* Adjust the maximum width of the carousel images to fit the container */
        .carousel-item img {
            max-width: 100%;
            height: auto;
        }
        .carousel-inner {
            width: 100%; /* Make the carousel images fill the width */
        }
    }
</style>
</head>

<body>
    <?php include('include/top_bar.php'); ?>

    <div class="container">
        <div class="row justify-content-center"> <!-- Center the division -->
            <div class="col-md-6" >
                <div id="myCarousel" class="carousel slide" data-ride="carousel" >
                    <!-- Indicators -->
                    <ol class="carousel-indicators" >
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Slides -->
                    <div class="carousel-inner" >
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="include/image/test1.jpg" alt="First slide">
                            <div class="carousel-caption"></div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="include/image/image2.jpg" alt="Second slide">
                            <div class="carousel-caption"></div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="include/image/test2.jpg" alt="Third slide">
                            <div class="carousel-caption"></div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true" ></span>
                        <span class="sr-only" >Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>

            <div class="search-form col-md-6">
                <form method="GET" action="">
                    <div class="form-row">
                        <div class="col-12">
                            <h5>Search for a used car</h5>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="make" class="form-control" placeholder="Brand"><br>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="model" class="form-control" placeholder="Model"><br>
                        </div>
                       <div class="col-md-3">
                            <input type="number" name="min_price" class="form-control" placeholder="Min Price"><br>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="max_price" class="form-control" placeholder="Max Price"><br>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="year" class="form-control" placeholder="Year"><br>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="submit" class="btn btn-primary btn-block" name="search" >Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">Available Cars</h2>
        <div class="row">

            <?php
$sql = "SELECT sellcars.car_id, sellcars.image1, sellcars.make, sellcars.model, sellcars.year, sellcars.price, user.firstname, user.lastname
        FROM sellcars 
        INNER JOIN user ON sellcars.sellers_id = user.user_id
        WHERE sellcars.sold = 0";

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // Initialize an array to store the conditions
    $conditions = [];

    // Check if make is specified
    if (!empty($_GET['make'])) {
        $make = $_GET['make'];
        $conditions[] = "sellcars.make LIKE '%$make%'";
    }

    // Check if model is specified
    if (!empty($_GET['model'])) {
        $model = $_GET['model'];
        $conditions[] = "sellcars.model LIKE '%$model%'";
    }

    // Check if year is specified
    if (!empty($_GET['year'])) {
        $year = $_GET['year'];
        $conditions[] = "sellcars.year = $year";
    }

    // Check if minimum price is specified
    if (!empty($_GET['min_price'])) {
        $minPrice = $_GET['min_price'];
        $conditions[] = "sellcars.price >= $minPrice";
    }

    // Check if maximum price is specified
    if (!empty($_GET['max_price'])) {
        $maxPrice = $_GET['max_price'];
        $conditions[] = "sellcars.price <= $maxPrice";
    }

    // Check if user has entered any search criteria
    if (!empty($conditions)) {
        // Combine the conditions using 'AND'
        $conditionStr = implode(' AND ', $conditions);

        // Add the combined condition to the SQL query
        $sql .= " AND ($conditionStr)";
    }
}

$sql .= " ORDER BY sellcars.posted_date DESC"; // Add this line to order by posted_date in descending order

$result = mysqli_query($conn, $sql);

// Define the default image path and filename
$defaultImage = 'include/image/noimage.jpg';

// Check if the query execution was successful
if ($result) {
    // Check if any cars are available
    if (mysqli_num_rows($result) > 0) {
        // Display available cars
       while ($row = mysqli_fetch_assoc($result)) {
    $carId = $row['car_id'];
    $carMake = $row['make'];
    $carModel = $row['model'];
    $carYear = $row['year'];
    $carPrice = $row['price'];
    $userFirstName = $row['firstname'];
    $userLastName = $row['lastname'];

    // Check if the logged-in user is the owner of the car
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['sellers_id']) && $_SESSION['sellers_id'] == $row['sellers_id']) {
        continue; // Skip this iteration and move to the next car
    }

    // Check if the car has an image available
    $carCover = 'uploaded_car/' . $row['image1'];
    if (empty($row['image1']) || !file_exists($carCover)) {
        $carCover = $defaultImage; // Use the default image
    }

    echo "<div class='col-sm-6 col-md-4 col-lg-3'>";
    echo "<div class='card car-listing'><a href='userViewcar.php?car_id=$carId' class='card-link'>";
    echo "<img src='$carCover' class='card-img-top' alt='No Image Available'>";
    echo "<div class='card-body'>";
    echo "<h2 class='card-title' style='margin-top: auto; color: black; '>$carMake $carModel</h2>";
    echo "<div class='card-text' style='color: black;'>";
    echo "<p><b>Year:</b> $carYear</p>";
    echo "<p><b>Price:</b> <span style='color: #007bff;'>₱ " . number_format($carPrice, 2, '.', ',') . "</span></p>";
    echo "<p><b>Seller:</b> $userFirstName $userLastName</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

        }
    } else {
        // No cars available
        echo "<p>No cars available.</p>";
    }
} else {
    // Query execution failed, display error message
    echo "Error: " . mysqli_error($conn);
}
?>


        </div>
</div>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
        <?php include('include/footer.php'); ?>
    </body>

    </html>