    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management System - Edit Client</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand" href="main.php">Customer Management System</a>
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
            <h2>Edit Client</h2>
            <?php
            include 'db_config.php';

            if (isset($_GET['company_id'])) {
                $companyId = $_GET['company_id'];
            $sql = "SELECT * FROM clients WHERE company_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $companyId);
            $stmt->execute();
            $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $client = $result->fetch_assoc();

                    if (isset($_POST['editSubmit'])) {

                        $newCompanyName = $_POST['companyName'];
                        $newContactPerson = $_POST['contactPerson'];
                        $newPhone = $_POST['phone'];
                        $newStreet = $_POST['street'];
                        $newHouseNumber = $_POST['houseNumber'];
                        $newCity = $_POST['city'];
                        $newPostcode = $_POST['postcode'];
                        $updateSql = "UPDATE clients SET company_name = ?, contact_person = ?, phone = ?, street = ?, housenumber = ?, city = ?, postcode = ? WHERE company_id = ?";
                        $stmt = $conn->prepare($updateSql);
                        $stmt->bind_param("ssssissi", $newCompanyName, $newContactPerson, $newPhone, $newStreet, $newHouseNumber, $newCity, $newPostcode, $companyId);


                        if ($stmt->execute()) {
                            echo '<p class="mt-3 text-center text-success">Client updated successfully!</p>';
                            $client['company_name'] = $newCompanyName;
                            $client['contact_person'] = $newContactPerson;
                            $client['phone'] = $newPhone;
                            $client['street'] = $newStreet;
                            $client['housenumber'] = $newHouseNumber;
                            $client['city'] = $newCity;
                            $client['postcode'] = $newPostcode;
                        } else {
                            echo '<p class="mt-3 text-danger text-center">Error updating client: ' . $conn->error . '</p>';
                        }
                        $stmt->close();
                    }

                    if (isset($_POST['deleteSubmit'])) {

                        $deleteSql = "DELETE FROM clients WHERE company_id = (?)";
                        $stmt = $conn->prepare($deleteSql);
                        $stmt->bind_param("i", $companyId);

                        if ($stmt->execute()) {
                            echo '<p class="mt-3 text-success">Client deleted successfully!</p>';
                            header("Location: main.php"); 
                            exit();
                        } else {
                            echo '<p class="mt-3 text-danger text-center">Error deleting client: ' . $conn->error . '</p>';
                        }
                        $stmt->close();
                    }


                    echo '
                    <form method="post" action="edit.php?company_id=' . $companyId . '">
                        <div class="form-group">
                            <label for="companyName">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="companyName" value="' . $client["company_name"] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="contactPerson">Contact Person</label>
                            <input type="text" class="form-control" id="contactPerson" name="contactPerson" value="' . $client["contact_person"] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="' . $client["phone"] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" id="street" name="street" value="' . $client["street"] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="houseNumber">House Number</label>
                            <input type="number" class="form-control" id="houseNumber" name="houseNumber" value="' . $client["housenumber"] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="' . $client["city"] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="postcode">Postcode</label>
                            <input type="number" class="form-control" id="postcode" name="postcode" value="' . $client["postcode"] . '" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="editSubmit">Edit Client</button>
                        <button type="submit" class="btn btn-danger mt-3" name="deleteSubmit">Delete Client</button>
                    </form>';
                } else {
                    echo '<p class="text-danger text-center">Client not found.</p>';
                }
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>