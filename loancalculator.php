<!DOCTYPE html>
<html>

<head>
    <title>Auto Loan Calculator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Monthly Payment Calculator</h1>
        <form id="autoLoanCalculatorForm">
            <div class="form-group">
                <label for="totalPrice">Total Price of Car:</label>
                <input type="text" class="form-control" id="totalPrice">
            </div>
            <div class="form-group">
                <label for="loanAmount">Down Payment:</label>
                <input type="number" class="form-control" id="loanAmount" required>
            </div>
            <div class="form-group">
                <label for="interestRate">Interest Rate (%):</label>
                <input type="number" class="form-control" id="interestRate" required>
            </div>
            <div class="form-group">
                <label for="loanTerm">Payment Term (months):</label>
                <input type="number" class="form-control" id="loanTerm" required>
            </div>
            <button type="button" class="btn btn-primary" id="calculateLoan">Calculate</button>

            <div id="monthlyPaymentResult" class="mt-3"></div>


        </form>
    </div>

    <!-- Add Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        // Function to calculate the auto loan payment
        function calculateAutoLoanPayment() {
            // Get input values
            var totalPrice = parseFloat(document.getElementById('totalPrice').value.replace('₱ ', '').replace(',', ''));
            var downPayment = parseFloat(document.getElementById('loanAmount').value);
            var interestRate = parseFloat(document.getElementById('interestRate').value) / 100;
            var loanTerm = parseFloat(document.getElementById('loanTerm').value);

            // Calculate loan amount
            var loanAmount = totalPrice - downPayment;

            // Calculate monthly interest rate
            var monthlyInterestRate = interestRate / 12;

            // Calculate monthly payment
            var monthlyPayment = (loanAmount * monthlyInterestRate) / (1 - Math.pow(1 + monthlyInterestRate, -loanTerm));

            // Display the result
            var monthlyPaymentResult = document.getElementById('monthlyPaymentResult');
            monthlyPaymentResult.innerHTML = 'Monthly Payment: ₱ ' + monthlyPayment.toFixed(2);
        }

        // Attach the calculateAutoLoanPayment function to the "Calculate" button click event
        document.getElementById('calculateLoan').addEventListener('click', calculateAutoLoanPayment);
    </script>
      <?php include('include/footer.php'); ?>
</body>

</html>
