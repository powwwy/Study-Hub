<?php
session_start();
require '../php/connect.php';

if (!isset($_SESSION['adminID'])) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupName = $_POST['group_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . "_" . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO studygroups (name, category, description, ImageURL) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $groupName, $category, $description, $imagePath);

    if ($stmt->execute()) {
        echo "Group created successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Group</title>
  <link rel="stylesheet" href="/Study-Hub/css/admin.css">
  <link rel="stylesheet" href="/Study-Hub/css/nav.css">
</head>
<body>
  <div class="container">
    <h2>Create New Group</h2>
    <form method="POST" action="create_group.php" enctype="multipart/form-data">
      <label for="group_name">Group Name</label>
      <input type="text" name="group_name" id="group_name" required>

      <div class="sidebar">
    <h2>Study Hub Admin</h2>
    <ul>
      <li><a href="/Study-Hub/Admin/create_groups.php">Create Groups</a></li>
      <li><a href="/Study-Hub/php/logout.php">Logout</a></li>
    </ul>
  </div>

      <label for="category">Category</label>
      <select name="category" id="category" required>
        <option value="">-- Select Category --</option>
        <option value="Engineering">Engineering</option>
        <option value="Business">Business</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Humanities">Humanities</option>
        <option value="Sciences">Sciences</option>
        <option value="Law">Law</option>
        <option value="Medicine">Medicine</option>
        <option value="Education">Education</option>
      </select>

      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4" required></textarea>

      <label for="image">Group Image</label>
      <input type="file" name="image" id="image" accept="image/*">

      <button type="submit">Create Group</button>
    </form>
  </div>
</body>
</html>
