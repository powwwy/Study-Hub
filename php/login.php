<?php
require 'connect.php'; // Include database connection

$studentID = intval($_POST['studentID']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE StudentID = ?");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['PasswordHash'])) {
        session_start();
        $_SESSION['studentID'] = $user['StudentID'];
        $_SESSION['name'] = $user['Name'];
        echo "Login successful. Welcome, " . $user['Name'];
        header("Location: ../User/profile.php"); // Redirect to user dashboard
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "Student ID not found.";
}
$conn->close();
?>
