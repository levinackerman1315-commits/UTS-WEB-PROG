<?php
// test_email.php - Test Email Configuration
require_once 'config.php';
require_once 'mailer.php';

echo "<h1>Test Email Configuration</h1>";
echo "<p>Testing email sending functionality...</p>";

// Test data
$test_email = "mikamika10kaka@gmail.com"; // Ganti dengan email Anda untuk testing
$test_name = "Test User";
$test_token = "test-token-123";

echo "<h3>Konfigurasi Email Saat Ini:</h3>";
echo "<ul>";
echo "<li><strong>SMTP Host:</strong> " . SMTP_HOST . "</li>";
echo "<li><strong>SMTP Port:</strong> " . SMTP_PORT . "</li>";
echo "<li><strong>Username:</strong> " . SMTP_USERNAME . "</li>";
echo "<li><strong>Password:</strong> " . (SMTP_PASSWORD ? "***SET***" : "<span style='color:red'>NOT SET</span>") . "</li>";
echo "<li><strong>From Email:</strong> " . SMTP_FROM_EMAIL . "</li>";
echo "</ul>";

echo "<h3>Test Kirim Email:</h3>";

$activation_link = SITE_URL . "/activate.php?token=" . $test_token;

$subject = "Test Email - User Management System";
$body = "
    <h2>Test Email</h2>
    <p>Halo $test_name,</p>
    <p>Ini adalah email test untuk memastikan konfigurasi email berfungsi.</p>
    <p>Link aktivasi test: <a href='$activation_link'>$activation_link</a></p>
    <p>Jika Anda menerima email ini, berarti konfigurasi email sudah benar!</p>
    <hr>
    <p>Test dilakukan pada: " . date('Y-m-d H:i:s') . "</p>
";

if (sendEmail($test_email, $test_name, $subject, $body)) {
    echo "<p style='color: green;'><strong>✅ SUCCESS:</strong> Email berhasil dikirim ke $test_email</p>";
    echo "<p><strong>Periksa inbox email Anda!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ FAILED:</strong> Gagal mengirim email</p>";
    echo "<p><strong>Cek error log di c:\\xampp\\php\\logs\\php_error_log</strong></p>";
}

echo "<hr>";
echo "<p><a href='register.php'>← Kembali ke Registrasi</a></p>";
?>