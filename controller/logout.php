<?php
session_start();
session_destroy();

// Remove persistence cookie
setcookie('auth_beetle', '', time() - 3600, "/");

header("Location: /beetlesystem/login");
exit;
?>
