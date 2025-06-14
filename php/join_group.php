<?php
session_start();
error_log("SESSION: " . print_r($_SESSION, true));

if (!isset($_SESSION['userID'])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

$userId = $_SESSION['userID'];
$groupId = $_POST['group_id'] ?? null;

if (!$groupId) {
  http_response_code(400);
  echo json_encode(["error" => "No group selected"]);
  exit;
}

require 'connect.php';

$stmt = $conn->prepare("SELECT * FROM groupmemberships WHERE UserID = ? AND GroupID = ?");
$stmt->bind_param("ii", $userId, $groupId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo json_encode(["message" => "Already in group"]);
} else {
  $joinedAt = date('Y-m-d H:i:s'); // current datetime
  $stmt = $conn->prepare("INSERT INTO groupmemberships (UserID, GroupID, JoinedAt) VALUES (?, ?, ?)");
  $stmt->bind_param("iis", $userId, $groupId, $joinedAt);
  if ($stmt->execute()) {
    echo json_encode(["message" => "Joined group"]);
  } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to join"]);
  }
}

