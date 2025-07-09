<?php
require 'connect.php';
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['studentID'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$studentId = $_SESSION['studentID'];

// Query to get the UserID that matches this studentID
$stmt = $conn->prepare("SELECT UserID FROM users WHERE StudentID= ?");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['userId' => $row['UserID']]);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>