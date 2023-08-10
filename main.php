<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management System - Main</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand ms-3" href="main.php">Customer Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item ">
                    <a class="nav-link" href="add.php">Add Client</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="logout.php">Logout</a> 
                </li>
            </ul>
        </div>
    </nav>

    <ul class="nav nav-pills nav-fill bg-light mb-4">
        <li class="nav-item">
            <a class="nav-link active" id="allClientsLink"  aria-current="page" href="main.php?filter=all">All Clients</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="myClientsLink" href="main.php?filter=my">My Clients</a>
        </li>
    </ul>

    <div class="row">

        <?php
        session_start(); 
        include 'db_config.php';

        $filter = $_GET['filter'] ?? 'all';

        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php");
            exit;
        }

        if ($filter === 'all') {
            $sql = "SELECT * FROM clients";
        } elseif ($filter === 'my') {
            $userId = $_SESSION['user_id'];
            $sql = "SELECT * FROM clients WHERE created_by = $userId";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4 mb-4 d-flex align-items-stretch "> 
                    <div class="card w-100">
                        <div class="card-body">
                            <h5 class="card-title">' . $row["company_name"] . '</h5>
                            <p class="card-text"> Contact: ' . $row["contact_person"] . '</p>
                            <p class="card-text"> Phone: ' . $row["phone"] . '</p>
                            <p class="card-text"> City: ' . $row["city"] . '</p>';

                            if ($row['created_by'] == $_SESSION['user_id']) {
                                echo '<a href="edit.php?company_id=' . $row["company_id"] . '" class="btn btn-primary">Edit</a>';
                            }
                            echo '
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p>No clients found.</p>';
        }
        $conn->close();
        ?>
    </div>
</div>

<script src="js/bootstrap.min.js"></script>
<script>
    // Get the current URL parameter for the filter
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');

    // Highlight the appropriate link based on the filter
    if (filter === 'all') {
        document.getElementById('allClientsLink').classList.add('active');
        document.getElementById('myClientsLink').classList.remove('active');
    } else if (filter === 'my') {
        document.getElementById('allClientsLink').classList.remove('active');
        document.getElementById('myClientsLink').classList.add('active');
    }
</script>

</body>
</html>