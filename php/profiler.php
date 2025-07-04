<?php
session_start();
require 'connect.php'; // DB connection

$groups = [];

// Ensure user is logged in
if (!isset($_SESSION['studentID'])) {
    echo "Not logged in.";
    exit;
}

$studentID = $_SESSION['studentID'];

// Fetch user info if needed (optional)
$stmt = $conn->prepare("SELECT * FROM users WHERE StudentID = ?");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit;
}

// Fetch groups user is part of
$stmt = $conn->prepare("
    SELECT g.GroupID, g.Name AS name, g.Category AS category, g.Description AS description, g.ImageURL as imageURL
    FROM studygroups g
    JOIN groupmemberships gm ON g.GroupID = gm.GroupID
    WHERE gm.UserID = ?
");
$stmt->bind_param("i", $studentID); 
$stmt->execute();
$result = $stmt->get_result();
$groups = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
