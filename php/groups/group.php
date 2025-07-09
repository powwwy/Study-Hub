<?php
require '../connect.php';
require '../profiler.php';

if (!isset($_SESSION['studentID']) || !isset($_GET['id'])) {
  header('Location: /Study-Hub/User/profile.php');
  exit;
}

$userId = $_SESSION['studentID'];
$groupId = intval($_GET['id']);

// Check membership
$check = $conn->prepare("SELECT * FROM groupmemberships WHERE UserID = ? AND GroupID = ?");
$check->bind_param("ii", $userId, $groupId);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
  echo "Access denied.";
  exit;
}

// Fetch group name
$group = $conn->prepare("SELECT Name FROM studygroups WHERE GroupID = ?");
$group->bind_param("i", $groupId);
$group->execute();
$nameResult = $group->get_result()->fetch_assoc();
$groupName = $nameResult['Name'];

// Fetch members
$members = $conn->prepare("
  SELECT u.Name 
  FROM users u 
  JOIN groupmemberships gm ON u.StudentID = gm.UserID 
  WHERE gm.GroupID = ?
");
$members->bind_param("i", $groupId);
$members->execute();
$memberResult = $members->get_result();

// Fetch user's groups for sidebar
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
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($groupName) ?> - Group Page</title>
  <!--<link rel="stylesheet" href="/Study-Hub/react-chat/build/static/css/main.445f4d08.css">-->
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

      <!-- 🔽 File Upload Section -->
      <div class="file-upload-section">
        <h3>Share a File with the Group</h3>
        <form action="upload_file.php?id=<?= $groupId ?>" method="POST" enctype="multipart/form-data">
          <input type="file" name="shared_file" required>
          <button type="submit" class="btn">Upload</button>
        </form>
      </div>

      <!-- 📂 Shared Files List -->
      <div class="file-list-section" style="margin-top: 1.5rem;">
        <h3>Shared Files</h3>
        <ul>
          <?php
          $uploadDir = "../../uploads/group_$groupId/";
          if (is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            foreach ($files as $file) {
              if ($file !== "." && $file !== "..") {
                echo "<li><a href='$uploadDir$file' download>" . htmlspecialchars($file) . "</a></li>";
              }
            }
          } else {
            echo "<li>No files uploaded yet.</li>";
          }
          ?>
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
    // React Chat will mount here
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
