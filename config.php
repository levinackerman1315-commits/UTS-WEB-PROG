<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'user_management_system');

// Email configuration - GMAIL SMTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'mikamika10kaka@gmail.com');
define('SMTP_PASSWORD', 'cnabqhltvgnicxav');
define('SMTP_FROM_EMAIL', 'mikamika10kaka@gmail.com');
define('SMTP_FROM_NAME', 'User Management System');

// Site URL - SESUAIKAN DENGAN FOLDER ANDA
// Cek path folder Anda di XAMPP
define('SITE_URL', 'http://localhost/user-management-system');

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>