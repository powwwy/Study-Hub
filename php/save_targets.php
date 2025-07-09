<?php
session_start();
if (!isset($_SESSION['studentID'])) {
  http_response_code(401);
  echo "Unauthorized";
  exit;
}

require 'connect.php';

$userId = $_SESSION['studentID'];
$groupId = $_POST['group_id'] ?? null;

// Collect CAT scores
$catScores = $_POST['cat_scores'] ?? [];
$catOutOf = $_POST['cat_outof'] ?? [];

// Sanitize and default values
for ($i = 0; $i < 5; $i++) {
  $catScores[$i] = isset($catScores[$i]) && $catScores[$i] !== '' ? floatval($catScores[$i]) : 0;
  $catOutOf[$i] = isset($catOutOf[$i]) && $catOutOf[$i] !== '' ? floatval($catOutOf[$i]) : 0;
}


$examScore = $_POST['exam_score'] ?? null;
$examOutOf = $_POST['exam_outof'] ?? null;

if (!$groupId || !$examScore || !$examOutOf) {
  echo "Missing or invalid data.";
  exit;
}

// Delete old targets for this user and group
$deleteStmt = $conn->prepare("DELETE FROM user_targets WHERE UserID = ? AND GroupID = ?");
if (!$deleteStmt) {
    die("Delete prepare failed: " . $conn->error);
}
$deleteStmt->bind_param("ii", $userId, $groupId);
$deleteStmt->execute();

// Insert new CAT targets
$insertStmt = $conn->prepare("INSERT INTO user_targets (UserID, GroupID, CategoryName, Score, OutOf) VALUES (?, ?, ?, ?, ?)");
if (!$insertStmt) {
    die("Insert prepare failed: " . $conn->error);
}
for ($i = 0; $i < 5; $i++) {
  $catName = "CAT " . ($i + 1);
  $insertStmt->bind_param("iisdd", $userId, $groupId, $catName, $catScores[$i], $catOutOf[$i]);
  $insertStmt->execute();
}

// Insert/Update exam target
$examStmt = $conn->prepare("REPLACE INTO user_exam_targets (UserID, GroupID, Score, OutOf) VALUES (?, ?, ?, ?)");
if (!$examStmt) {
    die("Exam prepare failed: " . $conn->error);
}
$examStmt->bind_param("iidd", $userId, $groupId, $examScore, $examOutOf);
$examStmt->execute();

header("Location: ../Metrics/targets.php?success=1");
exit;
