<?php
session_start();
require '../php/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminID = trim($_POST['AdminID']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE AdminID = ?");
    $stmt->bind_param("s", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($password === $admin['password']) {  // No hashing
            $_SESSION['adminID'] = $admin['AdminID'];
            header("Location: /Study-Hub/Admin/create_group.php");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Admin not found.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="/Study-Hub/css/admin.css" />
</head>
<body>

<div class="container">
  <h2>Admin Login</h2>
  <form method="POST" action="admin_login.php">
    <label for="AdminID">Admin ID</label>
    <input type="text" name="AdminID" id="AdminID" placeholder="Enter Admin ID" required />

    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Enter Password" required />

    <button type="submit">Login</button>
  </form>
</div>

</body>
</html>
