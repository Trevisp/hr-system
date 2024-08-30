<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jcoaches";
$xcport="400";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname,$xcport);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
