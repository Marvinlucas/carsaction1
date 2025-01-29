<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Check if the user is logged in
if (!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true) {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Handle user approval
if (isset($_GET['approve'])) {
    $user_id = $_GET['approve'];
    // Update the user's approval status in the database
    $sql = "UPDATE user SET approved = 1 WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Successfully approved user, redirect to user list
        header("Location: userList.php");
        exit();
    } else {
        // Handle the error
        echo "Error approving user: " . mysqli_error($conn);
    }
}

// Fetch available unapproved users from the database
$unapprovedUsersSql = "SELECT user_id, firstname, lastname, username, phone_number, email, profile_picture, complete_address, id_picture, selfie_picture FROM user WHERE approved = 1";
$unapprovedUsersResult = mysqli_query($conn, $unapprovedUsersSql);

if (isset($_GET['block'])) {
    $user_id = $_GET['block'];
    // Update the user's approval status in the database
    $sql = "UPDATE user SET approved = 0 WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Successfully blocked user, redirect to user list
        header("Location: userList.php");
        exit();
    } else {
        // Handle the error
        echo "Error blocking user: " . mysqli_error($conn);
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>CarSaction</title>
   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include('include/top_bar_admin.php'); ?>
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">View Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="idImage" src="" alt="ID Picture" class="img-fluid">
                    <img id="selfieImage" src="" alt="Selfie Picture" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

  


    <div class="container">
        <h2>List of Sellers</h2>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search by ID, First Name, Last Name">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" id="searchButton" type="button">
                    <i class="fas fa-search"></i>
                </button>
    </div>
</div> 
<div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Profile Picture</th>
                    <th>Complete Address</th>
                    <th>ID Picture</th>
                    <th>Selfie Picture</th>
                    <th>View</th>
                    <th>Action</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($unapprovedUsersResult)) { ?>
                    <tr>
                     <?php
                       echo '<tr>';
			            echo '<td>' . $row['user_id'] . '</td>';
			            echo '<td>' . $row['firstname'] . '</td>';
			            echo '<td>' . $row['lastname'] . '</td>';
			            echo '<td>' . $row['username'] . '</td>';
			            echo '<td>' . $row['phone_number'] . '</td>';
			            echo '<td>' . $row['email'] . '</td>';
			            echo '<td>' . $row['profile_picture'] . '</td>';
			            echo '<td>' . $row['complete_address'] . '</td>';
			            echo '<td id="id_picture_' . $row['user_id'] . '">' . $row['id_picture'] . '</td>';
			            echo '<td id="selfie_picture_' . $row['user_id'] . '">' . $row['selfie_picture'] . '</td>';
            ?>
                        <td>
                        	<button class="btn btn-primary view-btn" data-id="<?php echo $row['user_id']; ?>">View</button></td>
                        <td> <button class="btn btn-danger block-btn" data-id="<?php echo $row['user_id']; ?>">Block</button></td>
                    
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <script>
        $(document).ready(function () {
            $('.view-btn').click(function () {
                var user_id = $(this).data('id');
                var idPicture = $('#id_picture_' + user_id).text();
                var selfiePicture = $('#selfie_picture_' + user_id).text();

                var idImageUrl = 'upload_ID_face/' + idPicture;
                var selfieImageUrl = 'upload_ID_face/' + selfiePicture;

                $('#idImage').attr('src', idImageUrl);
                $('#selfieImage').attr('src', selfieImageUrl);

                $('#imageModal').modal('show');
            });
              $('.block-btn').click(function () {
            var user_id = $(this).data('id');
            // Redirect to the current page with the 'block' parameter
            window.location.href = 'userList.php?block=' + user_id;
        });
 
       $('#searchButton').click(function () {
    var searchTerm = $('#searchInput').val().trim().toLowerCase();
    if (searchTerm !== '') {
        $('.table tbody tr').hide().filter(function () {
            var sellerId = $(this).find('td:eq(0)').text(); // Assuming the seller_id is in the first column
            return sellerId.toLowerCase().indexOf(searchTerm) > -1;
        }).show();
    } else {
        $('.table tbody tr').show();
    }
});
});
	
    </script>

    <?php include('include/footer.php'); ?>
</body>

</html>
