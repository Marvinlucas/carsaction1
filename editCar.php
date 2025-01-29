<?php
session_start();
require_once 'include/config.php'; // Include your database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the car_id from the URL.
    $car_id = $_GET["car_id"];

    // Retrieve the updated data from the form.
    $new_year = $_POST["new_year"];
    $new_make = $_POST["new_make"];
    $new_model = $_POST["new_model"];
    $new_price = $_POST["new_price"];
     $new_downpayment = $_POST["new_downpayment"];
      $new_interest_rate = $_POST["new_interest_rate"];
      $new_loan_term = $_POST["new_loan_term"];
      $new_monthly_payment = $_POST["new_monthly_payment"];
   $new_location = $_POST["new_location"];
      $new_description = $_POST["new_description"];
      $new_old_price = $_POST["new_old_price"];


    // Validate and sanitize user input as needed.

    // Update the car data in the database.
    // Replace 'your_database' and 'your_table' with your actual database and table names.
    $sql = "UPDATE sellcars SET year = :new_year, make = :new_make, model = :new_model, price = :new_price, downpayment= :new_downpayment, interest_rate=:new_interest_rate, loan_term= :new_loan_term, monthly_payment= :new_monthly_payment, location = :new_location, description = :new_description, old_price = :new_old_price WHERE car_id = :car_id";

    // Execute the SQL query using prepared statements (assuming you're using PDO).
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":new_year", $new_year, PDO::PARAM_INT);
        $stmt->bindParam(":new_make", $new_make, PDO::PARAM_STR);
        $stmt->bindParam(":new_model", $new_model, PDO::PARAM_STR);
        $stmt->bindParam(":new_price", $new_price, PDO::PARAM_INT);
        $stmt->bindParam(":new_downpayment", $new_downpayment, PDO::PARAM_INT);
                $stmt->bindParam(":new_interest_rate", $new_interest_rate, PDO::PARAM_INT);
                $stmt->bindParam(":new_loan_term", $new_loan_term, PDO::PARAM_INT);
                $stmt->bindParam(":new_monthly_payment", $new_monthly_payment, PDO::PARAM_INT);
                 $stmt->bindParam(":new_location", $new_location, PDO::PARAM_STR);
                  $stmt->bindParam(":new_description", $new_description, PDO::PARAM_STR);
                   $stmt->bindParam(":new_old_price", $new_old_price, PDO::PARAM_INT);
        
        $stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
        $stmt->execute();
        // Redirect to a success page or display a success message.
        header("Location: userCarlist.php");
        exit();
    } catch (PDOException $e) {
        // Handle database errors.
        echo "Error: " . $e->getMessage();
    }
} else {
    // If it's not a POST request, retrieve the existing data from the database and prepopulate the form.
    $car_id = $_GET["car_id"];
    try {
        $sql = "SELECT year, make, model, price, downpayment, loan_term, interest_rate, monthly_payment, location, description, old_price FROM sellcars WHERE car_id = :car_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database errors.
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- HTML Form (update_form.php) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Car Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
      <?php include('include/top_bar.php'); ?>
    <div class="container mt-5">
        <h2>Update Car Details</h2>
        <form method="POST" action="editCar.php?car_id=<?php echo $_GET['car_id']; ?>">
            <div class="form-group">
                <label for="new_year">Year:</label>
                <input type="number" class="form-control" id="new_year" name="new_year" required value="<?php echo isset($car['year']) ? $car['year'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="new_make">Make:</label>
                <input type="text" class="form-control" id="new_make" name="new_make" required value="<?php echo isset($car['make']) ? $car['make'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="new_model">Model:</label>
                <input type="text" class="form-control" id="new_model" name="new_model" required value="<?php echo isset($car['model']) ? $car['model'] : ''; ?>">
                 <div class="form-group">
                <label for="new_price">Old Price:</label>
                <input type="number" class="form-control" id="new_old_price" name="new_old_price" required value="<?php echo isset($car['old_price']) ? $car['old_price'] : ''; ?>">
            </div>
            </div>
            <div class="form-group">
                <label for="new_price">Current Price:</label>
                <input type="number" class="form-control" id="new_price" name="new_price" required value="<?php echo isset($car['price']) ? $car['price'] : ''; ?>">
            </div>
              <div class="form-group">
                    <label for="price">Downpayment:</label>
                    <input type="number" class="form-control" id="new_downpayment" name="new_downpayment" value="<?php echo isset($car['downpayment']) ? $car['downpayment'] : ''; ?>">
                </div>


                <div class="form-group">
                    <label for="price">Monthly payment:</label>
                    <input type="number" class="form-control" id="new_monthly_payment" name="new_monthly_payment" onkeyup="formatPrice(this)" value="<?php echo isset($car['monthly_payment']) ? $car['monthly_payment'] : ''; ?>">
                </div>
              <div class="form-group">
                <label for="interestRate">Interest Rate (%):</label>
                <input type="number" class="form-control" id="new_interest_rate" name="new_interest_rate" step="0.01" min="0.00" max="9999.99" val value="<?php echo isset($car['interest_rate']) ? $car['interest_rate'] : ''; ?>">
              </div>
               <div class="form-group">
                <label for="loanTerm">Loan Term (months):</label>
                <input type="number" class="form-control" id="new_loan_term" name="new_loan_term"  value="<?php echo isset($car['loan_term']) ? $car['loan_term'] : ''; ?>">
            </div>

                <a href="loancalculator.php" id="openLoanCalculator">Open Payment Calculator if Needed!</a>

        

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" id="new_location" name="new_location" required  value="<?php echo isset($car['location']) ? $car['location'] : ''; ?>">
                </div>

               <div class="form-group">
    <label for="description">Description:</label>
    <textarea class="form-control" id="new_description" name="new_description" required><?php echo isset($car['description']) ? $car['description'] : ''; ?></textarea>

           
                </div>

            <button type="submit" class="btn btn-primary">Update Details</button>
        </form>
    </div>
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
