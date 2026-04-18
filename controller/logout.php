<?php
session_start();
session_destroy();

// Remove persistence cookie
setcookie('auth_beetle', '', time() - 3600, "/");

require_once '../core/db.php';
header("Location: " . BASE_URL . "admin");
exit;
?>
