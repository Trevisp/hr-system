<?php
session_start();
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';

    // Variable declaration and sanitization
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    // Fetch user data from the database
    $sql = "SELECT * FROM `users` WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    // Check if user exists
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($pwd, $user['pwd'])) {
            // Password is correct, start a session and redirect to main page
            $_SESSION['username'] = $user['fname'];
            header("Location: joes.php");
            exit();
        } else {
            // Password is incorrect
            $login_error = 'Invalid password.';
        }
    } else {
        // User does not exist
        $login_error = 'No user found with that email address.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Login</h1>
        
        <!-- Display login errors -->
        <?php if ($login_error): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error:</strong> <?= $login_error ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" placeholder="Enter your email address" name="email" required>
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" placeholder="Enter password" name="pwd" required>
            </div>
            <div class="text-center">
            <button type="submit" class="btn btn-primary">Login</button>
            <p>Dont have an account?<a href="register.php"></a></p>
            </div>
            
        </form>
    </div>
</body>
</html>
