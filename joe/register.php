<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['pwd'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];

        // Hash the password before storing it
        $hashed_pwd = password_hash($pwd, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (fname, lname, email, pwd) VALUES ('$fname', '$lname', '$email', '$hashed_pwd')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login page after successful registration
            header("Location: login.php");

        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <img src="images/logo.png" alt="" width="150px">
        </div>
    </div>
    <form action="register.php" method="post">
        <div class="container">
            <h1 class="text-center">Register</h1>
            <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" class="form-control" placeholder="Enter First Name" name="fname" required>
            </div>
            <div class="form-group">
                <label for="name">Last Name</label>
                <input type="text" class="form-control" placeholder="Enter Last Name" name="lname" required>
            </div>
            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control" placeholder="Enter email" name="email" required>
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" placeholder="Enter password" name="pwd" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                  <p>Already have an account?<a href="login.php">Login</a></p>
            </div>

        </div>
    </form>
</body>
</html>
