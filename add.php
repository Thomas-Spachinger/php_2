<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management System - Add Client</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand mx-3" href="main.php">Customer Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item">
                    <a class="nav-link" href="main.php">Back to Main</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Add Client</h2>
            <h3 class='mt-5'>Company</h3>
            <form method="post" action="add.php">
                <div class="form-group">
                    <label for="companyName">Company Name</label>
                    <input type="text" class="form-control" id="companyName" name="companyName" placeholder='Model Company' required>
                </div>
                <h3 class='mt-4'>Contact</h3>
                <div class="form-group">
                    <label for="contactPerson">Contact Person</label>
                    <input type="text" class="form-control" id="contactPerson" name="contactPerson" placeholder='Max Model' required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder='123-456789' required>
                </div>
                <h3 class='mt-4'>Address</h3>
                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" class="form-control" id="street" name="street" placeholder='Model Street' required>
                </div>
                <div class="form-group">
                    <label for="houseNumber">House Number</label>
                    <input type="number" class="form-control" id="houseNumber" name="houseNumber" placeholder='1' required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder='Model City' required>
                </div>
                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="number" class="form-control" id="postcode" name="postcode" placeholder='1234' required>
                </div>
                <button type="submit" class="btn btn-primary mt-5" name="addSubmit">Add Client</button>
            </form>
        </div>
    </div>
</div>

<?php
session_start(); 
include 'db_config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
        
if (isset($_POST['addSubmit'])) {
    $companyName = $_POST['companyName'];
    $contactPerson = $_POST['contactPerson'];
    $phone = $_POST['phone'];
    $street = $_POST['street'];
    $houseNumber = $_POST['houseNumber'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $createdBy = $_SESSION['user_id']; 


    $sql = "INSERT INTO clients (company_name, contact_person, phone, street, housenumber, city, postcode, created_by) 
            VALUES ('$companyName', '$contactPerson', '$phone', '$street', '$houseNumber', '$city', '$postcode', '$createdBy')";

    if ($conn->query($sql) === TRUE) {
        echo '<p class="mt-3 text-center text-success">Client added successfully!</p>';
    } else {
        echo '<p class="mt-3 text-center text-danger">Error adding client: ' . $conn->error . '</p>';
    }
}

$conn->close();
?>

<script src="js/bootstrap.min.js"></script>
</body>
</html>