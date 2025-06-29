<?php
session_start();
if (!isset($_SESSION['userID'])) {
  echo "Not logged in!";
  header("Location: /Study-Hub/User/login.html");
  exit;
} else {
  echo "Logged in as user #" . $_SESSION['userID'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="/Study-Hub/css/home.css">
<link rel="stylesheet" href="/Study-Hub/css/nav.css">
<title>Study Hub - User Landing</title>
</head>
<body>

    <div class="sidebar">
    <h2>Study Hub</h2>
    <ul>
      <li><a href="/Study-Hub/User/home.php">Home</a></li>
      <li><a href="/Study-Hub/User/profile.php">Profile</a></li>
      <li><a href="/Study-Hub/Metrics/metrics.html">Metrics</a></li>
      <li><a href="/Study-Hub/User/pomodoro.php">Pomodoro</a></li>
      <li><a href="/Study-Hub/Metrics/targets.html">Targets</a></li>
      <li><a href="/Study-Hub/User/files.php">Files</a></li>
      <li><a href="/Study-Hub/php/logout.php">Logout</a></li>
    </ul>
  </div>
  <div class = "heads">
  <h1>Study Hub</h1>
  <h2>Connect. Collaborate. Conquer.</h2>
</div>
  <hr>
<nav>
  <div class="search-bar">
    <input type="text" placeholder="Search units..." disabled title="Search is not functional yet" />
  </div>
  <a class="btn-login" id="open-login-btn" href="login.html" >Login / Signup</a>
</nav>

  <h2 style="text-align:center;">Join Now!</h2>
  <div id="groups-list" class="card-container"></div>

  <script>
function joinGroup(groupId) {
fetch('../php/join_group.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded'
  },
  body: `group_id=${groupId}`,
  credentials: 'include' 
})

  .then(res => res.json())
  .then(data => {
    if (data.message) {
      alert(data.message);
      // Optional: disable button or change UI
    } else if (data.error) {
      alert('Error: ' + data.error);
    }
  });
}

fetch('../php/get_groups.php')
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById('groups-list');
    container.innerHTML = data.map(group => `
      <div class="card">
        <h3>${group.name}</h3>
        <p><strong>Category:</strong> ${group.category}</p>
        <p>${group.description}</p>
        <button class="join-btn" onclick="joinGroup(${group.groupID})">Join Group</button>

      </div>
    `).join('');
  });
</script>

</body>
</html>
