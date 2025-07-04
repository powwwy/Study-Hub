<?php
// Create connection
$conn = new mysqli("localhost", "root", "", "study_hub", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
