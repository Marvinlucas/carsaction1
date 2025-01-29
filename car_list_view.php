<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true) {
    $username = $_SESSION["username"];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Fetch car data from your database (you should have a similar query)
$query = "SELECT * FROM sellcars";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>

<head>
    <title>CarSaction</title>
  
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
    .custom-btn {
        height: 40px; /* Set the desired height */
    }
</style>
</head>

<body>
    <?php include('include/top_bar_admin.php'); ?>

    <!-- Modal for displaying car images -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Car Images</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators"></ol>
                    <div class="carousel-inner"></div>
                    <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="container">
        <h2>List of Cars</h2>
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Sold</th>
                    <th>Seller ID</th>
                    <th>Mileage</th>
                    <th>Plate Number Ending</th>
                    <th>Color</th>
                    <th>Transmission</th>
                    <th>Owner</th>
                    <th>Tittle</th>
                    <th>Image1</th>
                    <th>Image2</th>
                    <th>Image3</th>
                    <th>Image4</th>
                    <th>Image5</th>
                    <th>Image6</th>
                    <th>Image7</th>
                    <th>Image8</th>
                    <th>Image9</th>
                    <th>Image10</th>
                    <th>Posted Date</th>
                    <th>Action</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <!-- Display car data in table cells -->
                        <td><?php echo $row['car_id']; ?></td>
                        <td><?php echo $row['make']; ?></td>
                        <td><?php echo $row['model']; ?></td>
                        <td><?php echo $row['year']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['sold']; ?></td>
                        <td><?php echo $row['sellers_id']; ?></td>
                        <td><?php echo $row['mileage']; ?></td>
                        <td><?php echo $row['plate_number_ending']; ?></td>
                        <td><?php echo $row['color']; ?></td>
                        <td><?php echo $row['transmission']; ?></td>
                        <td><?php echo $row['owner']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['image1']; ?></td>
                        <td><?php echo $row['image2']; ?></td>
                        <td><?php echo $row['image3']; ?></td>
                        <td><?php echo $row['image4']; ?></td>
                        <td><?php echo $row['image5']; ?></td>
                        <td><?php echo $row['image6']; ?></td>
                        <td><?php echo $row['image7']; ?></td>
                        <td><?php echo $row['image8']; ?></td>
                        <td><?php echo $row['image9']; ?></td>
                        <td><?php echo $row['image10']; ?></td>
                        <td><?php echo $row['posted_date']; ?></td>
                       
              <td>
    <!-- Add a hidden div to wrap the description with a unique ID -->
    <div id="description_<?php echo $row['car_id']; ?>" class="description" style="display: none;">
        <?php echo $row['description']; ?>
    </div>
    </td>
  <td>
    <!-- Add a "See More" button and Delete button in the same column -->
    <div class="btn-group">
        <button class="btn btn-secondary see-more custom-btn" data-carid="<?php echo $row['car_id']; ?>">SeeMore</button>
        <form method="post" action="delete_car_in_list.php">
            <input type="hidden" name="car_id" value="<?php echo $row['car_id']; ?>">
            <button type="submit" class="btn btn-danger custom-btn" name="delete_car">Delete</button>
        </form>
    </div>
</td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function displayImagesModal(images) {
        var baseImageUrl = "include/uploads/"; // Base URL for image directory
        var carouselInner = $('.carousel-inner');
        var carouselIndicators = $('.carousel-indicators');
        
        carouselInner.empty();
        carouselIndicators.empty();
        
        for (var i = 0; i < images.length; i++) {
            var imageUrl = baseImageUrl + images[i];
            var activeClass = i === 0 ? 'active' : '';
            
            carouselInner.append(`
                <div class="carousel-item ${activeClass}">
                    <img src="${imageUrl}" class="d-block w-100" alt="Image ${i + 1}">
                </div>
            `);
            
            carouselIndicators.append(`
                <li data-target="#carouselIndicators" data-slide-to="${i}" class="${activeClass}"></li>
            `);
        }
        
        $('#imageModal').modal('show');
    }

    $(document).ready(function () {
        $('.view-btn').click(function () {
            var images = [];
            for (var i = 1; i <= 10; i++) {
                var image = $(this).closest('tr').find(`td:eq(${15 + i})`).text().trim();
                if (image !== '') {
                    images.push(image);
                }
            }

            displayImagesModal(images);
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Handle "See More" button click
        $('.see-more').click(function () {
            var carId = $(this).data('carid');
            var descriptionDiv = $('#description_' + carId);

            // Toggle visibility of the description
            descriptionDiv.toggle();

            // Change the button text based on visibility
            if (descriptionDiv.is(':visible')) {
                $(this).text('See Less');
            } else {
                $(this).text('SeeMore');
            }
        });
    });
</script>

    <?php include('include/footer.php'); ?>
</body>

</html>