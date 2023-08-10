<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>

<div class="container mt-5 mx-auto" style="width: 350px">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation" >
            <a class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" role="tab" aria-controls="login-tab-pane" aria-selected="true" href="#login">Login</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-tab-pane" role="tab" aria-controls="register-tab-pane" aria-selected="false" href="#register">Register</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane container fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
            <h2>Login</h2>
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                </div>
                <button type="submit" class="btn btn-primary mt-1" name="loginSubmit">Login</button>
            </form>
        </div>

        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>

        <div class="tab-pane container fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
            <h2>Register</h2>
            <form method="post" action="index.php" onsubmit="return validateEmail()">
                <div class="form-group">
                    <label for="registerName">Name</label>
                    <input type="text" class="form-control" id="registerName" name="registerName" required>
                </div>
                <div class="form-group">
                    <label for="registerEmail">Email</label>
                    <input type="email" class="form-control" id="registerEmail" name="registerEmail" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Password</label>
                    <input type="password" class="form-control" id="registerPassword" name="registerPassword" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2" name="registerSubmit">Register</button>
            </form>
        </div>
    </div>
</div>

<script>
    function validateEmail() {
        var emailInput = document.getElementById('registerEmail');
        var emailValue = emailInput.value;
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/;
        if (!emailRegex.test(emailValue)) {
            alert('Please enter a valid email address.');
            return false;
        }
        return true;
    }
</script>

</body>
</html>

<?php
session_start();
include 'db_config.php'; 

if (isset($_POST['loginSubmit'])) {
    $loginEmail = $_POST['loginEmail'];
    $loginPassword = $_POST['loginPassword'];

    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $loginEmail);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPasswordFromDb);

    if ($stmt->fetch() && password_verify($loginPassword, $hashedPasswordFromDb)) {
        
        $_SESSION['user_id'] = $userId;
        header("Location: main.php");
        exit();
    } else {
        $loginError = "Invalid email or password.";
    }
    $stmt->close();
}   

if (isset($_POST['registerSubmit'])) {
    $registerName = $_POST['registerName'];
    $registerEmail = $_POST['registerEmail'];
    $registerPassword = $_POST['registerPassword'];
    if (!filter_var($registerEmail, FILTER_VALIDATE_EMAIL) || !strpos($registerEmail, '.')) {
        $registerError = "Please enter a valid email address.";
    } 
    else {
    $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);
    }
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $registerName, $registerEmail, $hashedPassword);

    if ($stmt->execute()) {
        
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $registerEmail);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $_SESSION['user_id'] = $userId;
        header("Location: main.php");
        exit();
        
    } else {
        $registerError = "Error registering user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<?php if (isset($loginError)) : ?>
    <div class="alert alert-danger w-50 mt-3 mx-auto text-center"><?php echo $loginError; ?></div>
<?php endif; ?>
<?php if (isset($registerSuccess)) : ?>
    <div class="alert alert-success w-50 mt-3 mx-auto text-center"><?php echo $registerSuccess; ?></div>
<?php elseif (isset($registerError)) : ?>
    <div class="alert alert-danger w-50 mt-3 mx-auto text-center"><?php echo $registerError; ?></div>
<?php endif; ?>

