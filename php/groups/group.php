<?php
require '../connect.php';
require '../profiler.php';

if (!isset($_SESSION['studentID']) || !isset($_GET['id'])) {
  header('Location: /Study-Hub/User/profile.php');
  exit;
}

$userId = $_SESSION['studentID'];
$groupId = intval($_GET['id']);

// 📤 Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['shared_file'])) {
  $file = $_FILES['shared_file'];
  $filename = time() . "_" . basename($file['name']);
  $uploadFolder = "../../uploads/group_$groupId/";

  if (!is_dir($uploadFolder)) {
    mkdir($uploadFolder, 0777, true);
  }

  $targetPath = $uploadFolder . $filename;
  $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
  $allowedTypes = ['pdf', 'docx', 'jpg', 'jpeg', 'png', 'zip'];

  if (in_array($fileType, $allowedTypes) && $file['size'] <= 10 * 1024 * 1024) {
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
      $stmt = $conn->prepare("INSERT INTO group_files (group_id, file_name, file_path) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $groupId, $filename, $targetPath);
      $stmt->execute();
      header("Location: group.php?id=$groupId");
      exit;
    }
  }
}

// ❌ Handle file delete
if (isset($_GET['delete'])) {
  $fileId = intval($_GET['delete']);
  $stmt = $conn->prepare("SELECT file_path FROM group_files WHERE id = ? AND group_id = ?");
  $stmt->bind_param("ii", $fileId, $groupId);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($row = $res->fetch_assoc()) {
    unlink($row['file_path']);
    $del = $conn->prepare("DELETE FROM group_files WHERE id = ?");
    $del->bind_param("i", $fileId);
    $del->execute();
    header("Location: group.php?id=$groupId");
    exit;
  }
}

// ✅ Check group membership
$check = $conn->prepare("SELECT * FROM groupmemberships WHERE UserID = ? AND GroupID = ?");
$check->bind_param("ii", $userId, $groupId);
$check->execute();
$result = $check->get_result();
if ($result->num_rows === 0) {
  echo "Access denied.";
  exit;
}

// ✅ Fetch group name
$group = $conn->prepare("SELECT Name FROM studygroups WHERE GroupID = ?");
$group->bind_param("i", $groupId);
$group->execute();
$nameResult = $group->get_result()->fetch_assoc();
$groupName = $nameResult['Name'];

// ✅ Fetch members
$members = $conn->prepare("
  SELECT u.Name 
  FROM users u 
  JOIN groupmemberships gm ON u.StudentID = gm.UserID 
  WHERE gm.GroupID = ?
");
$members->bind_param("i", $groupId);
$members->execute();
$memberResult = $members->get_result();

// ✅ Fetch user's groups for sidebar
$groupQuery = $conn->prepare("
  SELECT sg.GroupID, sg.Name
  FROM studygroups sg
  JOIN groupmemberships gm ON sg.GroupID = gm.GroupID
  WHERE gm.UserID = ?
");
$groupQuery->bind_param("i", $userId);
$groupQuery->execute();
$groupsResult = $groupQuery->get_result();
$groups = $groupsResult->fetch_all(MYSQLI_ASSOC);

// ✅ Fetch shared files
$files = [];
$fileQ = $conn->prepare("SELECT id, file_name, uploaded_at FROM group_files WHERE group_id = ? ORDER BY uploaded_at DESC");
$fileQ->bind_param("i", $groupId);
$fileQ->execute();
$files = $fileQ->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($groupName) ?> - Group Page</title>
  <link rel="stylesheet" href="/Study-Hub/css/App.css">
  <link rel="stylesheet" href="/Study-Hub/css/groups.css">
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <h2>Study Hub</h2>
      <ul>
        <li><a href="/Study-Hub/User/home.php">Home</a></li>
        <li><a href="/Study-Hub/User/profile.php">Profile</a></li>
        <li><a href="/Study-Hub/Metrics/metrics.html">Metrics</a></li>
        <li><a href="/Study-Hub/User/pomodoro.html">Pomodoro</a></li>
        <li><a href="/Study-Hub/Metrics/targets.php">Targets</a></li>
        <li><strong style="margin-left: 1rem;">My Groups</strong></li>
        <?php foreach ($groups as $g): ?>
          <li>
            <a href="/Study-Hub/php/groups/group.php?id=<?= $g['GroupID'] ?>">
              <?= htmlspecialchars($g['Name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
        <li><a href="/Study-Hub/php/logout.php">Logout</a></li>
      </ul>
    </div>

    <div class="main-content">
      <h2><?= htmlspecialchars($groupName) ?></h2>
      <p>Welcome to the group page, <?= htmlspecialchars($user['Name']) ?>!</p>

      <!-- File Sharing Section -->
      <div class="file-upload-section">
        <h3>Share a File</h3>
        <form method="POST" enctype="multipart/form-data">
          <input type="file" name="shared_file" required>
          <button type="submit">Upload</button>
        </form>
      </div>

      <!-- File List -->
      <div class="file-list-section" style="margin-top: 1.5rem;">
        <h3>Shared Files</h3>
        <ul>
          <?php if (count($files) > 0): ?>
            <?php foreach ($files as $f): ?>
              <li>
                📎 <a href="../../uploads/group_<?= $groupId ?>/<?= htmlspecialchars($f['file_name']) ?>" download>
                  <?= htmlspecialchars($f['file_name']) ?>
                </a>
                - <?= date("M d, Y H:i", strtotime($f['uploaded_at'])) ?>
                [<a href="?id=<?= $groupId ?>&delete=<?= $f['id'] ?>" onclick="return confirm('Delete this file?')">Delete</a>]
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li>No files shared yet.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <div class="rightbar">
      <h3>Group Members</h3>
      <ul>
        <?php while ($row = $memberResult->fetch_assoc()): ?>
          <li><?= htmlspecialchars($row['Name']) ?></li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>

  <div id="react-chat" data-group-id="<?= htmlspecialchars($groupId) ?>"></div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const chatElement = document.getElementById('react-chat');
      if (chatElement) {
        chatElement.setAttribute('data-group-id', '<?= htmlspecialchars($groupId) ?>');
      }
    });
  </script>
  <script src="/Study-Hub/react-chat/build/static/js/main.826deaa2.js" defer></script>
</body>
</html>
