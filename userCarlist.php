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

// Fetch available cars from the database
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stmt = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$user_id = $row["user_id"];

// Fetch available cars from the database for the logged-in seller
$stmt = $conn->prepare("SELECT * FROM sellcars WHERE sellers_id = ?");
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);

?>


    <!DOCTYPE html>
    <html>

    <head>
        <title>Car Listing</title>
        
    </head>
       <style >
            .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    h2 {
        margin-bottom: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 10px;
        text-align: left;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .car-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 5px;
    }

    .no-image {
        font-style: italic;
        color: #999999;
    }

    .actions {
        width: 200px;
    }

    .actions button {
        margin-right: 5px;
        padding: 5px 10px;
        font-size: 12px;

    }

    .actions .delete {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .actions .sold {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    a.btn.btn-sm.btn-success {
        width: 130px;
    }

    a.btn.btn-sm.btn-info {
        width: 130px;
    }

    .actions .available {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
       </style>
    <body>
        <?php include('include/top_bar.php'); ?>

        <div class="container ">

            
            <div class="row">
            
            <h2>Your Car Listings</h2>
        <?php if (!empty($cars)) : ?>
        <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
<!-- Mark Sold Modal -->
<div class="modal fade" id="markSoldModal" tabindex="-1" role="dialog" aria-labelledby="markSoldModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markSoldModalLabel">Mark Car as Sold</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="markSoldForm" action="mark_sold.php" method="POST">
                <input type="hidden" id="carId" name="carId" value="">
                <div class="modal-body">
                     <div class="form-group">
                        <label for="soldPrice"><i>Buyer Name:</i></label>
                        <input type="text" class="form-control" id="buyer_name" name="buyer_name" placeholder="Enter Buyer Name" required>
                    </div>
                    <div class="form-group">
                        <label for="soldPrice"><i>What Price Sold this Car?</i></label>
                        <input type="number" class="form-control" id="soldPrice" name="soldPrice" placeholder="Enter sold price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Mark as Sold</button>
                </div>
            </form>
        </div>
    </div>
</div>




  <?php foreach ($cars as $car) : ?>
    <tr>
        
                   <td class="car-image"><?php echo $car['image1'];?></td>
                    <td><?php echo $car['make']; ?></td>
                    <td><?php echo $car['model']; ?></td>
                    <td><?php echo $car['year']; ?></td>
                    <td><?php echo number_format($car['price'], 2, '.', ','); ?></td>
                   <td class="actions">
                        <div class="btn-group" role="group" aria-label="Car Actions">
                            <div class="btn-action" style="margin-right: 5px;">
                                <a href="editCar.php?car_id=<?php echo $car['car_id']; ?>"
                                    onclick="return confirm('Are you sure you want to edit this car?')"
                                    class="btn btn-sm btn-primary" >Edit</a>
                            </div>
                            <div class="btn-action"style="margin-right: 5px;">
                                <a href="delete_car.php?id=<?php echo $car['car_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this car?')"
                                    class="btn btn-sm btn-danger">Delete</a>
                            </div>
                            <?php if ($car['sold'] == 0) : ?>
        <div class="btn-action"  style="margin-right: 5px;">
           <a style="color: white;" class="btn btn-sm btn-success mark-sold-btn" data-car-id="<?php echo $car['car_id']; ?>" data-toggle="modal" data-target="#markSoldModal">Mark as Sold</a>
        </div>
                            <?php else : ?>
                            <div class="btn-action" style="margin-right: 5px;">
                                <a href="mark_available.php?id=<?php echo $car['car_id']; ?>"
                                    onclick="return confirm('Mark this car as available?')" class="btn btn-sm btn-info">Mark as Available</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <?php else : ?>
        <?php echo "<div class= 'alert alert-danger' role='alert'>No cars found.</div>" ?>
        <?php endif; ?>
    </div>
<script>
$(document).ready(function() {
  // Loop through each row of the table
  $(".car-image").each(function() {
    var imageName = $(this).text(); // Get the image file name from the table cell
    if (imageName.trim() === '') {
      $(this).addClass('no-image').text('No Image'); // If no image file name, display "No Image" text
    } else {
      // Build the image URL based on the "uploaded_car" directory
      var imageUrl = 'uploaded_car/' + imageName;
      
      // Display the image
      $(this).empty().append($('<img>').attr('src', imageUrl).addClass('car-image'));
    }
  });
});
</script>

<script>
$(document).ready(function() {
    // Handle button click to set the car ID in the modal form
    $(".mark-sold-btn").click(function() {
        var carId = $(this).data("car-id");
        $("#carId").val(carId); // Update the hidden input field with the car ID
    });
});
</script>




        </div>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
        <?php include('include/footer.php'); ?>
    </body>

    </html>