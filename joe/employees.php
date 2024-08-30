<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];

    $sql = "INSERT INTO employees (first_name, last_name, position, department, email, salary)
            VALUES ('$first_name', '$last_name', '$position', '$department', '$email', '$salary')";

    if ($conn->query($sql) === TRUE) {
        echo "New employee added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="modal-header-content">
    <span>Add employee</span>
</div>
<div class="modal-container">
    <form action="employees.php" method="POST">
        <label for="fname">First Name</label>
        <input type="text" id="first_name" name="first_name">
        <br>
        <label for="lname">Last Name</label>
        <input id="last_name" name="last_name" type="text">
        <br>
        <label for="position">Position</label>
        <input id="position" name="position" type="text">
        <br>
        <label for="department">Department</label>
        <input id="department" name="department" type="text">
        <br>
        <label for="email">Email</label>
        <input id="email" name="email" type="email">
        <br>
        <label for="salary">Salary</label>
        <input id="salary" name="salary" type="text">
        <br>
        <button id="confirmBtn" type="submit">Confirm</button>
    </form>
</div>
</body>
</html>
