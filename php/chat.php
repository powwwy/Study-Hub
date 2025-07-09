<?php
require 'connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['studentID'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$userId = $_SESSION['studentID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Send message
  $groupId = intval($_POST['groupId']);
  $message = trim($_POST['message']);

  if (!$message) {
    http_response_code(400);
    echo json_encode(['error' => 'Empty message']);
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO groupmessages (GroupID, UserID, Message, Timestamp) VALUES (?, ?, ?, NOW())");
  $stmt->bind_param("iis", $groupId, $userId, $message);
  $stmt->execute();

  echo json_encode(['success' => true]);
  exit;

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Get messages
  $groupId = intval($_GET['groupId']);

  $stmt = $conn->prepare("
    SELECT u.Name, gm.Message, gm.Timestamp 
    FROM groupmessages gm 
    JOIN users u ON gm.UserID = u.StudentID 
    WHERE gm.GroupID = ? 
    ORDER BY gm.Timestamp ASC
  ");
  $stmt->bind_param("i", $groupId);
  $stmt->execute();
  $result = $stmt->get_result();

  $messages = [];
  while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
  }

  echo json_encode($messages);
  exit;
}
