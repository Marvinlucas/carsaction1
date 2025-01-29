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

// Fetch reports from the database
$reports = array(); // Initialize an array to store report data

$query = "SELECT id, reported_username, report_reasons, report_message, reported_car_id, report_image, timestamp FROM report_users ORDER BY timestamp DESC"; // Add 'ORDER BY' clause to sort by timestamp in descending order
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>CarSaction - Admin View</title>
   
</head>

<body>
    <?php include('include/top_bar_admin.php'); ?>

    <!-- Modal -->
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
                    <img id="reportImage" src="" alt="report_image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2>Reports</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Reporter Username</th>
                        <th>Report Reasons</th>
                        <th>Report Message</th>
                        <th>Reported Car ID</th>
                        <th>Report Image</th>
                        <th>Report Timestamp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['id']); ?></td>
                            <td><?php echo htmlspecialchars($report['reported_username']); ?></td>
                            <td><?php echo htmlspecialchars($report['report_reasons']); ?></td>
                            <td><?php echo htmlspecialchars($report['report_message']); ?></td>
                            <td><?php echo htmlspecialchars($report['reported_car_id']); ?></td>
                            <td><?php echo htmlspecialchars($report['report_image']); ?></td>
                            <td><?php echo htmlspecialchars($report['timestamp']); ?></td>
                            <td>
                                <button class="btn btn-primary view-btn" data-image="<?php echo $report['report_image']; ?>">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.view-btn').click(function () {
                var reportImage = $(this).data('image');
                var reportImageUrl = 'report_image/' + reportImage;
                $('#reportImage').attr('src', reportImageUrl);
                $('#imageModal').modal('show');
            });
        });
    </script>

    <?php include('include/footer.php'); ?>
</body>

</html>
