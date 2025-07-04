<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['studentID'])) {
  http_response_code(401);
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}
require 'connect.php';
$studentID = $_SESSION['studentID'];
$groupID = $_POST['group_id'] ?? null;

if (!$groupID) {
  http_response_code(400);
  echo json_encode(["error" => "No group selected"]);
  exit;
}

// Log for debugging
error_log("Trying to add student $studentID to group $groupID");

$stmt = $conn->prepare("SELECT * FROM groupmemberships WHERE UserID = ? AND GroupID = ?");
$stmt->bind_param("ii", $studentID, $groupID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo json_encode(["message" => "Already in group"]);
} else {
  $joinedAt = date('Y-m-d H:i:s');
  $stmt = $conn->prepare("INSERT INTO groupmemberships (UserID, GroupID, JoinedAt) VALUES (?, ?, ?)");
  $stmt->bind_param("iis", $studentID, $groupID, $joinedAt);
  if ($stmt->execute()) {
    echo json_encode(["message" => "Joined group"]);
  } else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to join"]);
  }
}
?>