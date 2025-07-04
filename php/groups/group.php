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
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($groupName) ?> - Group Page</title>
  <meta charset="UTF-8">

  <link rel="stylesheet" href="groups.css">
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
          <li><a href="/Study-Hub/php/groups/group.php?id=<?= $g['GroupID'] ?>">
            <?= htmlspecialchars($g['name']) ?>
          </a></li>
        <?php endforeach; ?>
        <li><a href="/Study-Hub/php/logout.php">Logout</a></li>
      </ul>
    </div>

    <div class="main-content">
      <h2><?= htmlspecialchars($groupName) ?></h2>
      <!-- Add group-specific content here -->
      <p>Welcome to the group page.</p>
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
</body>
</html>
