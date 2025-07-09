<?php
require 'connect.php';
session_start();
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['groupId']) || !isset($input['message']) || !isset($_SESSION['studentID'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing data"]);
    exit;
}

$groupId = intval($input['groupId']);
$message = trim($input['message']);
$studentId = $_SESSION['studentID'];

$userStmt = $conn->prepare("SELECT UserID FROM users WHERE StudentID = ?");
$userStmt->bind_param("s", $studentId);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    http_response_code(400);
    echo json_encode(["error" => "Student not found"]);
    exit;
}

$userId = $userResult->fetch_assoc()['UserID'];

$stmt = $conn->prepare("INSERT INTO groupmessages (GroupID, UserID, Message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $groupId, $userId, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
}
?>
