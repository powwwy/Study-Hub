<?php
require 'connect.php';

if (!isset($_GET['groupId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing groupId']);
    exit;
}

$groupId = intval($_GET['groupId']);

$stmt = $conn->prepare("
  SELECT gm.Message, gm.Timestamp, gm.UserID, u.Name 
  FROM groupmessages gm 
  JOIN users u ON gm.UserID = u.UserID 
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

header('Content-Type: application/json');
echo json_encode($messages);
?>
