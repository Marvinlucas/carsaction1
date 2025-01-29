<?php
session_start();
require_once 'include/config.php';
require_once 'include/head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Dz5/Ky5KdwohF5j5j5GSd5S+3v5FO4CpLj2q5v5CpLj2q5v5" crossorigin="anonymous">
</head>
<body>
 <?php include('include/top_bar_rev.php'); ?>

    <div class="container mt-5">
        <h1 class="text-center">About Us: Your Trusted Destination for Secure Online Automobile Transactions</h1>
        <p class="text-center">Welcome to our premier online platform dedicated to revolutionizing the way you buy and sell automobiles. With a commitment to safety, transparency, and convenience, we have created a secure online marketplace that empowers individuals and businesses alike to make informed decisions when it comes to their automotive needs.</p>

        <h2 class="mt-4">Why Choose Us:</h2>
        <ol>
            <li><strong>Uncompromised Security:</strong> Your safety is our top priority. We have implemented state-of-the-art security measures to ensure that your personal and financial information is safeguarded at all times. Buy and sell with confidence, knowing that your data is protected.</li><br>
            <li><strong>Verified Listings:</strong> We go the extra mile to ensure that every vehicle listed on our platform is thoroughly vetted and accurately represented. Our team verifies important details, such as ownership history, mileage, and maintenance records, so you can trust the information provided.</li><br>
            <li><strong>Transparent Transactions:</strong> Say goodbye to hidden fees and unexpected surprises. We promote transparent transactions, making it easy for buyers and sellers to understand the costs involved and negotiate with confidence.</li><br>
            <li><strong>User-Friendly Interface:</strong> Our platform is designed with you in mind. Whether you're a first-time buyer or a seasoned seller, our intuitive interface makes it easy to navigate and conduct transactions efficiently.</li><br>
            <li><strong>Comprehensive Tools:</strong> From advanced search filters to valuation tools, we provide you with the resources you need to make informed decisions. Find the perfect vehicle or list your automobile for sale with ease.</li><br>
            <li><strong>Customer Support:</strong> We're here for you every step of the way. Our dedicated customer support team is ready to assist you with any questions or concerns, ensuring a smooth and hassle-free experience.</li><br>
            <li><strong>Eco-Friendly Initiatives:</strong> We believe in sustainability. We encourage environmentally responsible choices by highlighting eco-friendly vehicles and providing resources for green automotive practices.</li><br>
            <li><strong>Nationwide Reach:</strong> Our platform connects buyers and sellers across the nation, giving you access to a diverse range of vehicles and a broader market.</li>
        </ol>

        <p class="text-center mt-4"><strong>Join Us Today:</strong> Discover the future of buying and selling automobiles online. Whether you're looking for your dream car or ready to sell your vehicle, we invite you to join our community of automotive enthusiasts who value security, transparency, and convenience above all else. Together, we're driving the future of automobile transactions.</p>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
</body>
</html>
