<div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                 <a href="#" class="centered-link"><img src="include/image/cars.png" style="width: 35px; height: 35px; border-radius: 50%;"> CARsaction</a>
            </div>

            <ul class="list-unstyled components">
                <h3 class="ml-2">Menu</h3>
                <li class="active">
               
                <li>
                    <a href="index.php">Home</a>
                   
                </li>
                <li>
                    <a href="about.php">About us</a>
                </li>
                <li>
                    <a href="userLogin.php">Log in</a>
                </li>
            
            </ul>

        

<!-- Accordion Section 1 -->
<li class="list-unstyled CTAs">
    <a class="nav-link" href="#" role="button" onclick="toggleAccordion('accordionSection1')">
        <i class="fas fa-chevron-right"></i> safety tips
    </a>
    <div class="collapse" id="accordionSection1">
      <div class="pl-3">
    <p style="color: white;">If you're planning to buy a car online from sellers, it's crucial to prioritize safety and make informed decisions. Here are some safety tips to consider:</p>

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

    <p style="color: white;">By following these safety tips, you can have a more secure and successful experience when buying a car online from sellers.</p>
</div>
    </div>
</li>

<!-- Accordion Section 3 -->
<li class="list-unstyled CTAs">
    <a class="nav-link" href="#" role="button" onclick="toggleAccordion('accordionSection3')">
        <i class="fas fa-chevron-right"></i> How to use loan Calculator
    </a>
    <div class="collapse" id="accordionSection3">
        <div class="pl-3">

            <!-- Content for Accordion Section 3 goes here -->
            <p style="margin-left: 20px; color: white;">Using a  <a href="loanCalculator.php" style="color: skyblue;">loan calculator</a> is a straightforward process that can help you estimate your monthly loan payments and plan your finances effectively. Here's a step-by-step tutorial on how to use a loan calculator:</p>

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

            <p style="margin-left: 20px; color: white;">Using a loan calculator empowers you to make well-informed financial choices and helps you avoid unexpected surprises when it comes to loan repayments.</p>

        </div>
    </div>
</li>

    <!-- End of accordion sections -->
</ul>

               
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
<div id="menu-links">
    <ul class="list-unstyled components" style="display: flex;">
        <li style="flex: 1; padding: 0 10px;">
            <a href="index.php"><i class="fa fa-home">Home</i></a>
        </li>
        <li style="display: flex; justify-content: center; align-items: center; padding: 0 10px;">
    <a href="userLogin.php" id="notification-link">
        <i class="fa fa-bell" aria-hidden="true"><span id="notification-count" class="badge badge-danger"></span></i>
    </a>
</li>
        <li style="flex: 1; padding: 0 10px;">
            <a href="userLogin.php" id="message-link"><i class="fa fa-commenting-o" aria-hidden="true"></i><span id="message-count" class="badge badge-danger"></span></a>
        </li>
    </ul>
</div>


                </div>
            </nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
<script>
    $(document).ready(function() {
        // Function to update the notification count
        function updateNotificationCount() {
            $.ajax({
                url: 'notif_count.php', // URL to the PHP script
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Update the notification count on success
                    $('#notification-count').text(data.count);
                },
                error: function() {
                    // Handle errors if any
                    console.error('Failed to fetch notification count');
                }
            });
        }

        // Call the function to update the notification count on page load
        updateNotificationCount();

        // Optionally, you can also set a timer to update the count periodically
        setInterval(updateNotificationCount, 60000); // Update every 60 seconds (adjust as needed)
    });
</script>
<!-- Add this JavaScript code before the closing </body> tag -->
<script>
    $(document).ready(function() {
        // Function to update the notification count
        function updateNotificationCount() {
            $.ajax({
                url: 'get_loan_count.php', // URL to the PHP script
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Update the notification count on success
                    $('#loan-count').text(data.count);
                },
                error: function() {
                    // Handle errors if any
                    console.error('Failed to fetch notification count');
                }
            });
        }

        // Call the function to update the notification count on page load
        updateNotificationCount();

        // Optionally, you can also set a timer to update the count periodically
        setInterval(updateNotificationCount, 60000); // Update every 60 seconds (adjust as needed)
    });
</script>


   