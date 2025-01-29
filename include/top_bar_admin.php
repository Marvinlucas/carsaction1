<div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                 <a href="#" class="centered-link"><img src="include/image/cars.png" style="width: 35px; height: 35px; border-radius: 50%;"> CARsaction</a>
            </div>

            <ul class="list-unstyled components">
                <h3 class="ml-2">Menu</h3>
                  <div class="position-sticky">
                <ul class="nav flex-column">
                     <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle "
    href="#" id="userDropdown" role="button" data-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i>
    &nbsp;<?php echo $_SESSION['username']; ?>
</a>
            
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="admin_manage_account.php">Manage Account</a>
                      <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="addAdmin.php">Add New Admin</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            
                      </li>
                          <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"
                    href="#" id="carDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i>&nbsp;User
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="carDropdown">
                    <a class="dropdown-item" href="add_user.php">Add User</a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item" href="userPending.php">User Request</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="userList.php">Users List</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="car_list_view.php"><i class="fas fa-car"></i> Car List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="report_user.php"><i class="fas fa-flag"></i> Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link "
                    href="message_user.php"><i class="fas fa-home"></i> Message</a>
            </li>

        

               
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
            <a href="admin_dashboard.php"><i class="fa fa-home">Home</i></a>
       
    </ul>
</div>
                </div>
            </nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    // Toggle sidebar on mobile screens
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }
</script>



   