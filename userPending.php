<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';

// Include the send_email.php file to access the sendApprovalEmail function
require_once 'send_email.php';

?>

<!DOCTYPE html>
<html>

<head>
    <title>CarSaction</title>
    
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

    <div class="container mt-4">
        <h2>Pending Sellers Registrations</h2>
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
    <?php
    $query = "SELECT * FROM user WHERE status = 'pending' ORDER BY user_id ASC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } else {
        while ($row = mysqli_fetch_array($result)) {
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
            echo '<td><button class="btn btn-primary view-btn" data-id="' . $row['user_id'] . '">View</button></td>';
           echo '<td>';
    echo '<form action="userPending.php" method="POST">';
    echo '<input type="hidden" name="id" value="' . $row['user_id'] . '"/>';

    echo '<div class="d-flex justify-content-between">';
    echo '<button type="submit" class="btn btn-success mr-2" name="approve">Approve</button>'; // Add mr-2 for right margin
    echo '<button type="submit" class="btn btn-danger" name="deny">Decline</button>';
    echo '</div>';
    echo '</form>';
    echo '</td>';
    echo '</tr>';
}
    }
    ?>
</tbody>
</table>
       
  <?php
// ...

if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    $update = "UPDATE user SET status = 'Approved', approved = 1 WHERE user_id = '$id'";
    $result = mysqli_query($conn, $update);

    if ($result) {
        // Get the user's email and first name
        $query = "SELECT email, firstname FROM user WHERE user_id = '$id'";
        $userData = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($userData);

        if (sendApprovalEmail($user['email'], $user['firstname'])) {
            echo '<script type="text/javascript">';
            echo 'alert("User Approved and Email Sent!");';
            echo 'window.location.href = "userPending.php";';
            echo '</script>';
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("User Approved, but Email Sending Failed.");';
           
            echo '</script>';
        }
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}


if (isset($_POST['deny'])) {
    $id = $_POST['id'];

    $update = "UPDATE user SET status = 'Denied', approved = 0 WHERE user_id = '$id'";
    $result = mysqli_query($conn, $update);

    if ($result) {
        echo '<script type="text/javascript">';
        echo 'alert("User Denied!");';
        echo 'window.location.href = "userPending.php";';
        echo '</script>';
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
// ...

    ?>
</div>
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

   <!-- After including Bootstrap JavaScript -->
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
    });
</script>

   
    <?php include('include/footer.php'); ?>
</body>

</html>
