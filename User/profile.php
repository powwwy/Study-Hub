<?php 

require '../php/profiler.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile</title>
  <link rel="stylesheet" href="/Study-Hub/css/profile.css">
  <link rel="stylesheet" href="/Study-Hub/css/nav.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Study Hub</h2>
    <ul>
      <li><a href="/Study-Hub/User/home.php">Home</a></li>
      <li><a href="/Study-Hub/User/profile.php">Profile</a></li>
      <li><a href="/Study-Hub/Metrics/metrics.html">Metrics</a></li>
      <li><a href="/Study-Hub/User/pomodoro.php">Pomodoro</a></li>
       <li><a href="/Study-Hub/Metrics/targets.php">Targets</a></li>
      <li><strong style="margin-left: 1rem;">My Groups</strong></li>
     <?php foreach ($groups as $g): ?>
  <li><a href="/Study-Hub/User/group.php?id=<?= $g['GroupID'] ?>"> <?= htmlspecialchars($g['name']) ?></a></li>
<?php endforeach; ?>
      <li><a href="/Study-Hub/User/files.php">Files</a></li>
      <li><a href="/Study-Hub/php/logout.php">Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="profile-box">
      <h2>Welcome, <?= htmlspecialchars($user['Name']) ?>!</h2>
      <p><strong>Student ID:</strong> <?= $user['StudentID'] ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?></p>
      <p><strong>Course:</strong> <?= htmlspecialchars($user['Course']) ?></p>

      <div class="groups">
        <h3>Your Study Groups:</h3>
        <?php if (count($groups) > 0): ?>
          <?php foreach ($groups as $group): ?>
            <div class="group-card">
              <h4><?= htmlspecialchars($group['name']) ?></h4>
              <p><strong>Category:</strong> <?= htmlspecialchars($group['category']) ?></p>
              <p><?= htmlspecialchars($group['description']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>You haven’t joined any groups yet.</p>
          <p><a href="/Study-Hub/User/home.php" class="to-home">Explore</a></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
