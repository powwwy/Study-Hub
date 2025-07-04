<?php
require 'connect.php';// Include database connection

// Sanitize input
$studentID = intval($_POST['studentID']);
$name = trim($_POST['name']);
$course = trim($_POST['course']);
$email = strtolower(trim($_POST['email']));
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if student ID or email already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE StudentID = ? OR Email = ?");
$stmt->bind_param("is", $studentID, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Student ID or Email already exists.";
} else {
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (StudentID, Name, Course, Email, PasswordHash) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $studentID, $name, $course, $email, $password);
    if ($stmt->execute()) {
        echo "Signup successful!";
        header("Location: ../User/index.html"); // Redirect to login page
    } else {
        echo "Signup failed: " . $conn->error;
    }
}
$conn->close();
?>
