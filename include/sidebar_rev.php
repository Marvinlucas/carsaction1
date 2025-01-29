<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="index_user.php">CarSaction</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link <?php if ($currentPage === 'home') echo 'active-page'; ?>"
                    href="buyerIndex.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="notifications.php" id="notificationIcon">
                    <i class="fas fa-bell"></i>
                    <span class="badge badge-danger" id="notificationCount">1</span>
                </a>
            </li>
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
                <a class="nav-link" href="sellerChatlist.php" >
                    <i class="fas fa-envelope"></i>Message
                </a>
            </li>
                </div>
            </li>
        </ul>
    </div>
</nav>