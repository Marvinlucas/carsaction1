<?php
require_once 'include/head.php';
?>


<!DOCTYPE html>
<html>
<head>
    <title>Navbar Review</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Include custom CSS for sidebar -->
    <link href="styles.css" rel="stylesheet">
    <style>
        /* Custom styling for the sidebar navigation */
        .sidebar {
            position: fixed;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background-color: #f8f9fa; /* Background color for the sidebar */
            border-right: 1px solid #dee2e6; /* Right border for the sidebar */
            z-index: 5
        }

        .nav-link {
            color: #333; /* Link text color */
        }

        .nav-link:hover {
            color: #007bff; /* Link text color on hover */
        }

        .active .nav-link {
            font-weight: bold; /* Bold font for the active link */
            color: #007bff; /* Active link text color */
        }
         /* Styles for the top navigation bar */
       .top-nav {
            background-color: #007bff; /* Background color for the top navigation */
            color: #fff; /* Text color */
            padding: 10px 0; /* Padding for the top navigation items */
            text-align: center; /* Center-align the items */
            z-index: 5; /* Place it on top of other elements */
            display: flex; /* Use flexbox to control alignment */
            justify-content: space-between; /* Space evenly between items */


        }

        .top-nav .centered-link {
            flex-grow: 1; /* Allow the centered link to grow and take space */
            text-align: center; /* Center-align the link text */
        }

        .top-nav a {
            color: #fff; /* Link text color */
            text-decoration: none; /* Remove underlines from links */
            margin: 0 15px; /* Add spacing between items */
        }

     

        /* Media queries for different screen sizes */
        @media (max-width: 768px) {
            /* Styles for screens smaller than 768px (e.g., mobile devices) */
            .sidebar {
                display: none; /* Hide the sidebar by default */
            }

            .sidebar.active {
                display: block; /* Show the sidebar when active (toggled) */
                z-index: 8; /* Ensure sidebar is on top of content */
                width: 250px; /* Adjust the sidebar width as needed */
            }

            /* Add a button to toggle the sidebar on small screens */
            #sidebarToggle {
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 7;
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
            }
              .nav-link {
            color: #fff; /* Link text color */
        }

        .nav-link:hover {
            color: #007bff; /* Link text color on hover */
        }

        .active .nav-link {
             /* Bold font for the active link */
            color: black; /* Active link text color */
        }

          

            /* Close icon for hiding the sidebar */
            #closeIcon {
                display: block;
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 24px;
                cursor: pointer;
            }
        
    }

        @media (min-width: 769px) {
            /* Styles for screens larger than 768px (e.g., desktop) */
            #closeIcon {
                display: none; /* Hide the close icon in desktop view */
            }
        }
    </style>
</head>
<body>
   <div class="container-fluid">
    <div class="row">
        <!-- Top Navigation Bar -->
        <div class="col-md-12 top-nav">
            <a href="#" class="centered-link"><i class="fas fa-car"></i> Carsaction</a>
            <div class="right-links">
              <a class="link <?php if ($currentPage === 'home') echo 'active-page'; ?>"
                    href="sellerIndex.php"><i class="fas fa-home"></i> Home</a>
                <a href="sellerChatlist.php"><i class="fas fa-envelope"></i></a>
            </div>
        </div>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (hidden by default on larger screens) -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <h1 style="font-size: 25px">Seller Platform</h1>
            <div id="closeIcon" onclick="toggleSidebar()">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="position-sticky">
                <ul class="nav flex-column">
                     <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?php if ($currentPage === 'user') echo 'active-page'; ?>"
                    href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i>
                    &nbsp;<?php echo $username; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="buyers_manage_account.php">Manage Account</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
                      </li>
                         <li class="nav-item">
                <a class="nav-link" href="loanApproval.php" id="loanApprovalButton">
                    <i class="fas fa-bell"></i> Loan Approval
                    <span class="badge badge-danger">3</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?php if ($currentPage === 'mycar') echo 'active-page'; ?>"
                    href="#" id="carDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-car"></i>&nbsp;Sell Your Car
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="carDropdown">
                    <a class="dropdown-item" href="sellCar.php">Add New</a>
                </div>
            </li>
    
        <!-- Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Your page content goes here -->
        </main>
    </div>
</div>



<!-- Add a button to toggle the sidebar on small screens -->
<div id="sidebarToggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i> <!-- Menu icon -->
</div>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle sidebar on mobile screens
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }
</script>
<!-- Add this JavaScript code before the closing </body> tag -->
<script>
    // Function to toggle the accordion state
    function toggleAccordion(accordionId) {
        const accordion = document.getElementById(accordionId);
        accordion.classList.toggle("show");
    }
</script>
<script>
    $(document).ready(function() {
        // Show dropdown menu on mouseenter
        $('.dropdown').mouseenter(function() {
            $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(200);
        });

        // Hide dropdown menu on mouseleave
        $('.dropdown').mouseleave(function() {
            $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(200);
        });
    });
</script>

</body>
</html>
