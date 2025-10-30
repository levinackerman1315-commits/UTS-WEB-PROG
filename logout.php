<?php
// logout.php - Logout Handler
require_once 'session.php';

// Destroy session
logout();

// Redirect to login with message
setFlash('success', 'Anda telah berhasil logout.');
header('Location: login.php');
exit;
?>