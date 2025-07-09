<?php
require '../connect.php';
session_start();

if (!isset($_SESSION['studentID']) || !isset($_GET['id'])) {
  header('Location: /Study-Hub/User/profile.php');
  exit;
}

$userId = $_SESSION['studentID'];
$groupId = intval($_GET['id']);

// ✅ Check if user is part of the group
$check = $conn->prepare("SELECT * FROM groupmemberships WHERE UserID = ? AND GroupID = ?");
$check->bind_param("ii", $userId, $groupId);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
  die("Access denied.");
}

// ✅ Upload logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['shared_file'])) {
  $file = $_FILES['shared_file'];
  $filename = time() . "_" . basename($file['name']);
  $uploadFolder = "../../uploads/group_$groupId/";

  // Make folder if it doesn't exist
  if (!is_dir($uploadFolder)) {
    mkdir($uploadFolder, 0777, true);
  }

  $targetPath = $uploadFolder . $filename;
  $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
  $allowedTypes = ['pdf', 'docx', 'jpg', 'png', 'zip'];

  if (!in_array($fileType, $allowedTypes)) {
    echo "❌ File type not allowed.";
    exit;
  }

  if ($file['size'] > 10 * 1024 * 1024) { // 10MB limit
    echo "❌ File too large. Max 10MB.";
    exit;
  }

  if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // ✅ Successful upload
    header("Location: group.php?id=$groupId");
    exit;
  } else {
    echo "❌ File upload failed.";
    exit;
  }
} else {
  echo "❌ Invalid request.";
}
?>
