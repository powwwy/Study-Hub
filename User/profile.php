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
      <li><a href="/Study-Hub/User/pomodoro.html">Pomodoro</a></li>
       <li><a href="/Study-Hub/Metrics/targets.php">Targets</a></li>
      <li><strong style="margin-left: 1rem;">My Groups</strong></li>
     <?php foreach ($groups as $g): ?>
  <li><a href="/Study-Hub/php/groups/group.php?id=<?= $g['GroupID'] ?>"> <?= htmlspecialchars($g['name']) ?></a></li>
<?php endforeach; ?>
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
<h3>Your Study Groups:</h3>
      <div class="groups">
        <?php if (count($groups) > 0): ?>
          <?php foreach ($groups as $group): ?>
            <div class="group-card">
              <?php if ($group['imageURL']): ?>
                <img src="/Study-Hub/php/<?= htmlspecialchars($group['imageURL']) ?>" alt="Group Image" class="group-image">
              <?php else: ?>
                <p>No image available.</p>
              <?php endif; ?>
              <h4><?= htmlspecialchars($group['name']) ?></h4>
              <p><strong>Category:</strong> <?= htmlspecialchars($group['category']) ?></p>
              <p><?= htmlspecialchars($group['description']) ?></p>
              
              <a href="/Study-Hub/php/groups/group.php?id=<?= $group['GroupID'] ?>" class="view-group">View Group</a>
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
