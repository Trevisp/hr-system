<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $interviewId = (int)$_POST['id']; // Casting to integer for security and correct type handling

        $sql = "UPDATE interviews SET status = 'accepted' WHERE id = $interviewId";

        if ($conn->query($sql) === TRUE) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error: missing id';
    }
} else {
    echo 'error: invalid request';
}
?>
?>
