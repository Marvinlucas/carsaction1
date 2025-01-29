<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carsaction1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Database configuration
$db_host = "localhost"; // Replace with your database host
$db_name = "carsaction1"; // Replace with your database name
$db_user = "root"; // Replace with your database user
$db_pass = ""; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
    die();
}


// Database configuration
define('DB_SERVER', 'localhost'); // Replace with your database server address
define('DB_USERNAME', 'root'); // Replace with your database username
define('DB_PASSWORD', ''); // Replace with your database password
define('DB_NAME', 'carsaction1'); // Replace with your database name

// Function to validate user credentials
function validateUser($username, $password)
{
    global $conn;

    // Sanitize the input data
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the username and password match
    $sql = "SELECT * FROM user WHERE username='$username'";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $storedPassword = $row['password'];

        // Compare the plain text password with the stored password
        if ($password === $storedPassword) {
            // User found, login successful
            return true;
        }
    }

    // User not found or invalid credentials
    return false;
}// Function to validate admin credentials
function validateAdmin($conn, $username, $password) {
    // Query the database to retrieve admin data for the provided username
    $sql = "SELECT * FROM admin WHERE admin_username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Admin username exists, retrieve user data
        $user = $result->fetch_assoc();

        // Verify the hashed password
        return $user;
    } else {
        // Admin username does not exist
        return false;
    }
}

?>
