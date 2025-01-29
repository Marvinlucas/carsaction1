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
        }

        .nav-link {
            color: #333; /* Link text color */
        }

        .nav-link:hover {
            color: #007bff; /* Link text color on hover */
        }

        .active .nav-link {
           
            color: #007bff; /* Active link text color */
        }

           /* Styles for the top navigation bar */
       .top-nav {
            background-color: #33658a; /* Background color for the top navigation */
            color: #fff; /* Text color */
            padding: 10px 0; /* Padding for the top navigation items */
            text-align: center; /* Center-align the items */
            z-index: 0; /* Place it on top of other elements */
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
                z-index: 1; /* Ensure sidebar is on top of content */
                width: 250px; /* Adjust the sidebar width as needed */
            }

            /* Add a button to toggle the sidebar on small screens */
            #sidebarToggle {
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 0;
                background-color: #33658a;
                color: #fff;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
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
          .top-nav {
            background-color: #33658a; /* Background color for the top navigation */
            color: #fff; /* Text color */
            padding: 10px 0; /* Padding for the top navigation items */
            text-align: center; /* Center-align the items */
            z-index: 0; /* Place it on top of other elements */
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
         .nav-link {
            color: black; /* Link text color */
        }

        .nav-link:hover {
            color: #007bff; /* Link text color on hover */
        }

        .active .nav-link {
            color: black; /* Active link text color */
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
           <a href="#" class="centered-link"><img src="include/image/cars.png" style="width: 35px; height: 35px; border-radius: 50%;"> CARsaction</a>
          
        </div>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (hidden by default on larger screens) -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <h1 style="font-size: 30px">Welcome to Buy and Sell Cars</h1>
            <div id="closeIcon" onclick="toggleSidebar()">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item <?php if ($currentPage === 'home') echo 'active'; ?>">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item <?php if ($currentPage === 'about') echo 'active'; ?>">
                        <a class="nav-link" href="about.php"><i class="fas fa-info"></i> About Us</a>
                    </li>
                    <li class="nav-item <?php if ($currentPage === 'login') echo 'active'; ?>">
                        <a class="nav-link" href="userLogin.php"><i class="fas fa-user"></i> Login</a>
                    </li>
      <!-- Accordion Section 1 -->
<li class="nav-item">
    <a class="nav-link" href="#" role="button" onclick="toggleAccordion('accordionSection1')">
        <i class="fas fa-chevron-right"></i> safety tips
    </a>
    <div class="collapse" id="accordionSection1">
      <div class="pl-3">
    <p>If you're planning to buy a car online from sellers, it's crucial to prioritize safety and make informed decisions. Here are some safety tips to consider:</p>

    <ul style="list-style-type: disc; margin-left: 2px;">
        <li><strong>Research the Seller:</strong> Investigate the seller's reputation and history. Look for reviews and feedback from previous buyers.</li>
        <li><strong>Verify Vehicle Information:</strong> Ensure that the vehicle's details, such as mileage, VIN, and condition, match the description provided.</li>
        <li><strong>Request Vehicle History:</strong> Ask for the vehicle's history report, which can reveal any accidents or title issues.</li>
        <li><strong>Use Secure Payment Methods:</strong> Avoid sending cash and use secure payment methods like PayPal or escrow services.</li>
        <li><strong>Inspect the Car:</strong> If possible, arrange for a professional inspection or a test drive to verify the car's condition.</li>
        <li><strong>Be Cautious with Personal Information:</strong> Avoid sharing sensitive personal information with the seller until you're sure of their legitimacy.</li>
        <li><strong>Read the Fine Print:</strong> Carefully review the terms and conditions of the sale, including warranties and return policies.</li>
        <li><strong>Trust Your Instincts:</strong> If a deal seems too good to be true or raises suspicions, it's best to walk away.</li>
    </ul>

    <p>By following these safety tips, you can have a more secure and successful experience when buying a car online from sellers.</p>
</div>
    </div>
</li>

<!-- Accordion Section 3 -->
<li class="nav-item">
    <a class="nav-link" href="#" role="button" onclick="toggleAccordion('accordionSection3')">
        <i class="fas fa-chevron-right"></i> How to use loan Calculator
    </a>
    <div class="collapse" id="accordionSection3">
        <div class="pl-3">

            <!-- Content for Accordion Section 3 goes here -->
            <p style="margin-left: 20px;">Using a  <a href="loanCalculator.php">loan calculator</a> is a straightforward process that can help you estimate your monthly loan payments and plan your finances effectively. Here's a step-by-step tutorial on how to use a loan calculator:</p>

            <ul style="list-style-type: disc; margin-left: 2px;">
            
                <li><strong>Enter Loan Details</strong>:
                    <ul style="list-style-type: circle; margin-left: 1px;">
                        <li>Once you've found a loan calculator, open it. You'll typically see fields for inputting loan details.</li>
                        <li>Enter the loan amount: This is the total amount you plan to borrow.</li>
                        <li>Input the interest rate: This is the annual interest rate applied to your loan.</li>
                        <li>Specify the loan term: This is the number of months or years you will take to repay the loan.</li>
                    </ul>
                </li>
                <!-- Add similar styling to the rest of your bullet points -->
                <!-- ... -->
            </ul>

            <p style="margin-left: 20px;">Using a loan calculator empowers you to make well-informed financial choices and helps you avoid unexpected surprises when it comes to loan repayments.</p>

        </div>
    </div>
</li>

    <!-- End of accordion sections -->
</ul>
            </div>
        </nav>

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


</body>
</html>
