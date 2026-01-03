<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "arc123", "uas_web"); // Update password if needed

// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
?>