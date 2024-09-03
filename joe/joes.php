<?php
include 'config.php';
$login_error = '';


// Fetch user details
$user_sql = "SELECT * FROM users WHERE id = 21"; // Adjust as necessary
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
                  JOIN departments ON interviews.departmentCode = departments.departmentCode
                  WHERE interviews.status = 'pending'";

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
<li class="nav-item"><a href="" data-toggle="modal" data-target="#account">Account</a></li>
<li><a href="#" data-toggle="modal" data-target="#sure">Logout</a></li>
</ul>
</nav>
</div>
<div class="container">
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
  <?php while($row = $interviews->fetch_assoc()): ?>
<li id="interview-<?php echo $row['id']; ?>"> <!-- Add ID here -->
    <div class="list-item">
        <h3><?php echo $row['departmentName']; ?></h3>
        <p><?php echo $row['role']; ?></p>
        <button class="accept-btn" data-id="<?php echo $row['id']; ?>">Accept</button>
        <button class="reject-btn" data-id="<?php echo $row['id']; ?>">Reject</button>
    </div>
</li>
<?php endwhile; ?>
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
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="salary">Salary</label>
        <input type="text" class="form-control" id="salary" name="salary" required>
      </div>
      <button type="submit" class="btn btn-primary">Confirm</button>
      </form>
  </div>
      </div>

      <!-- Modal footer -->
    </div>
  </div>
</div>

<div class="modal fade" id="intModal" tabindex="-1" aria-labelledby="intModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="intModalLabel">Add Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="joes.php" method="POST">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" id="department" name="department" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" id="position" name="position" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- The Logout Confirmation Modal -->
<div class="modal" id="sure">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title">Logout Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        Are you sure you want to logout?
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <!-- Redirect to logout.php if confirmed -->
        <a href="logout.php" class="btn btn-success">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!--account modal-->
<div class="modal" id="account">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title">My Account</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal Body -->
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

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

  <button class="btn-primary" type="button" data-toggle="modal" data-target="#myModal">Add Employee</button>
  <button class="btn-primary" type="button" data-toggle="modal" data-target="#intModal">Add Interview</button>

</div>
<!-- Ensure the full jQuery library is included -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.accept-btn').on('click', function() {
        var interviewId = $(this).data('id');
        console.log("Accepting interview ID:", interviewId);

        $.ajax({
            url: 'accept_interview.php',
            type: 'POST',
            data: { id: interviewId },
            success: function(response) {
                console.log("Response from server:", response);
                if (response === 'success') {
                    $('#interview-' + interviewId).remove();
                } else {
                    alert('Failed to accept the interview: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert('An error occurred while processing the request.');
            }
        });
    });

    $('.reject-btn').on('click', function() {
        var interviewId = $(this).data('id');
        console.log("Rejecting interview ID:", interviewId);

        $.ajax({
            url: 'reject_interview.php',
            type: 'POST',
            data: { id: interviewId },
            success: function(response) {
                console.log("Response from server:", response);
                if (response === 'success') {
                    $('#interview-' + interviewId).remove();
                } else {
                    alert('Failed to reject the interview: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert('An error occurred while processing the request.');
            }
        });
    });
});

</script>

</body>
</html>
