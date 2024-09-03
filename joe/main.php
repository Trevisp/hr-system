<?php
include 'config.php';
$login_error = '';

session_start();

// Check if user_id is set in the session

// Fetch user details
$user_sql = "SELECT * FROM users WHERE user_id = $user_id"; // Adjust as necessary
$user_result = $conn->query($user_sql);

if (!$user_result) {
    die("Query failed: " . $conn->error);
}

$user = $user_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is for adding an employee
    if (isset($_POST['first_name'])) {
        $employeeNumber = $_POST['employeeNumber'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $departmentCode = isset($_POST['departmentCode']) ? $_POST['departmentCode'] : '';
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $email = $_POST['email'];

        $sql = "INSERT INTO employees (employeeNumber, first_name, last_name, departmentCode, position, salary, email)
                VALUES ('$employeeNumber', '$first_name', '$last_name', '$departmentCode', '$position', '$salary', '$email')";

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
        $departmentCode = isset($_POST['department']) ? $_POST['department'] : '';
        $role = $_POST['position'];

        $sql = "INSERT INTO interviews (departmentCode, role)
                VALUES ('$departmentCode', '$role')";

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
$employee_sql = "SELECT employees.*, departments.departmentName
                 FROM employees
                 JOIN departments ON employees.departmentCode = departments.departmentCode";
$employees = $conn->query($employee_sql);

// Fetch all interviews to display on the front page
$interview_sql = "SELECT interviews.*, departments.departmentName
                  FROM interviews
                  JOIN departments ON interviews.departmentCode = departments.departmentCode";
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
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="nav-wrapper">
<nav>
<ul class="nav-list">
<img id="icon" src="images/logo.png" alt="" width="70px">
<li class="nav-item"><a href="" data-toggle="modal" data-target="#account">Account</a></li>
<li><a href="#" data-toggle="modal" data-target="#sure">Logout</a></li>
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
                    <span><?php echo $row['departmentName']; ?></span> <!-- Echoing the department name -->
                    <br>
                    <span>Ksh <?php echo $row['salary']; ?></span>
                    <br>
                    <span><?php echo $row['email']; ?></span>
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
              <h3><?php echo $row['departmentName']; ?></h3>
              <p><?php echo $row['role']; ?></p>
              <button class="accept-btn">Accept</button>
              <button class="reject-btn">Reject</button>
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
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Employee</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form  action="joes.php" method="post">

        <div class="form-group">
        <label for="employeeNumber">Employee Number</label>
        <input type="text" class="form-control" id="employeeNumber" name="employeeNumber" required>
      </div>

      <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
      </div>

      <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
      </div>

      <div class="form-group">
        <label for="position">Position</label>
        <input type="text" class="form-control" id="position" name="position" required>
      </div>

      <div class="form-group">
        <label for="departmentCode">Department</label>
        <input type="text" class="form-control" id="departmentCode" name="departmentCode" required>
      </div>

      <div class="form-group">
        <label for="salary">Salary</label>
        <input type="number" class="form-control" id="salary" name="salary" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" name="add_employee">Add Employee</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Interview Modal -->
<div class="modal fade" id="interviewModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Interview</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form  action="joes.php" method="post">

      <div class="form-group">
        <label for="department">Department</label>
        <input type="text" class="form-control" id="department" name="department" required>
      </div>

      <div class="form-group">
        <label for="position">Position</label>
        <input type="text" class="form-control" id="position" name="position" required>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add Interview</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Account Modal -->
<div class="modal fade" id="account">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Account</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
        <label for="username">First Name</label>
        <input type="text" class="form-control" id="fname" value="<?php echo $user['fname']; ?>" readonly>
      </div>

      <div class="form-group">
        <label for="email">Last Name</label>
        <input type="email" class="form-control" id="email" value="<?php echo $user['lname']; ?>" readonly>
      </div>

      <div class="form-group">
        <label for="phone">Email</label>
        <input type="text" class="form-control" id="phone" value="<?php echo $user['email']; ?>" readonly>
      </div>
    </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Are you sure modal -->
<div class="modal fade" id="sure">
  <div class="modal-dialog">
    <div class="modal-content">
    <form method="post" action="logout.php">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p>Are you sure you want to log out?</p>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Logout</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
