<?php
//logout 
session_start();
// Unset all session variables
$_SESSION = array();
// Destroy the session
session_destroy();
// Redirect to login page
header("Location: ../user/index.html");
exit();
?>