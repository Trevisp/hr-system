<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is for adding an employee
    if (isset($_POST['first_name'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $departmentCode = isset($_POST['departmentCode']) ? $_POST['departmentCode'] : '';
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $email = $_POST['email'];
    
        $sql = "INSERT INTO employees (first_name, last_name, departmentCode, position, salary, email)
                VALUES ('$first_name', '$last_name', '$departmentCode', '$position', '$salary', '$email')";
    
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> New employee added successfully.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Check if the form is for adding an interview
    if (isset($_POST['department']) && isset($_POST['position']) && !isset($_POST['first_name'])) {
        $department = isset($_POST['department']) ? $_POST['department'] : '';
        $position = $_POST['position'];
        
        $sql = "INSERT INTO interviews (department, position) 
                VALUES ('$department', '$position')";

        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> New Interview added successfully.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}


// Fetch all employees to display on the front page
$employee_sql = "SELECT * FROM employees";
$employees = $conn->query($employee_sql);

// Fetch all interviews to display on the front page
$interview_sql = "SELECT * FROM interviews";
$interviews = $conn->query($interview_sql);

// Close the database connection after all queries
$conn->close();
?>



<html lang="en" dir="ltr">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <meta charset="utf-8">
    <title>Joe's Coaches</title>
    <link rel="stylesheet" href="style.css">
    <!--Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!--jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>

     <!--Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

     <!--Latest compiled JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="nav-wrapper">
<nav>
<ul class="nav-list">
<img id="icon" src="images/logo.png" alt="" width="70px">
<li class="nav-item"><a href="index.html">Account</a></li>
<li><a href="#">Logout</a></li>
</ul>
</nav>
</div>

<div class="employees">
<h2>Employees</h2>
<div class="employee-list">
<?php if ($employees->num_rows > 0): ?>
    <?php while($row = $employees->fetch_assoc()): ?>
    <li>
        <div class="employee-card">
            <img class="user-img" src="images/user.png" width="100px" alt="<?php echo $row['first_name']; ?>">
            <span>
                <div class="employment-details">
                    <span class="name-format"><?php echo $row['first_name']; ?></span>
                    <span class="name-format"><?php echo $row['last_name']; ?></span>
                    <br>
                    <span><?php echo $row['position']; ?></span>
                    <br>
                    <span><?php echo $row['department']; ?></span>
                    <br>
                    <span><?php echo $row['salary']; ?></span>
                    <br>
                    <span>Ksh<?php echo $row['email']; ?></span>
                </div>
            </span>
        </div>
    </li>
    <?php endwhile; ?>
<?php else: ?>
    <p>No employees found.</p>
<?php endif; ?>



</div>
</div>

<div class="interviews">
<h2>Upcoming Interviews</h2>
<div class="interview-list">
<?php if ($interviews->num_rows > 0): ?>
    <?php while($row = $interviews->fetch_assoc()): ?>
    <li>
        <div class="list-item">
            <h3><?php echo $row['department']; ?></h3>
            <p><?php echo $row['position']; ?></p>
            <button class="accept-btn" type="button" name="button">Accept</button>
            <button class="reject-btn" type="button" name="button">Reject</button>
        </div>
    </li>
    <?php endwhile; ?>
<?php else: ?>
    <p>No interviews found.</p>
<?php endif; ?>
</div>
</div>

<!-- MODAL -->
<!-- Employee Modal -->
<div id="employeeModal" class="modal">
<div class="modal-content">
<header class="modal-header">
<div class="modal-header-content">
    <span>Add employee</span>
</div>
</header>
<div class="modal-container">
    <form action="main.php" method="POST">
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
<footer class="modal-footer"></footer>
</div>
</div>

<!-- Interview Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add interview</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <form action="main.php" method="POST">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position" class="form-control">
                    </div>
                    <button id="confirmBtn" type="submit">Confirm</button>
                </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Add Interview
</button>
<button type="button" class="btn btn-primary" name="button" onclick="showAddEmpModal()">Add Employee</button>
<script type="text/javascript" src="main.js"></script>
</body>
</html>
