<?php
// activate.php - PERBAIKI VERSION
require_once 'connect.php';

$success = false;
$error = '';
$token = $_GET['token'] ?? '';

if ($token) {
    $token = mysqli_real_escape_string($conn, $token);
    
    // Cek token di database
    $result = mysqli_query($conn, "SELECT id, email, full_name FROM users WHERE activation_token = '$token' AND status = 'PENDING'");
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Update status ke ACTIVE
        $sql = "UPDATE users SET status = 'ACTIVE', activation_token = NULL, updated_at = NOW() WHERE id = {$user['id']}";
        
        if (mysqli_query($conn, $sql)) {
            $success = true;
            $user_name = $user['full_name'];
            $user_email = $user['email'];
        } else {
            $error = "Gagal mengaktivasi akun: " . mysqli_error($conn);
        }
    } else {
        $error = "Token tidak valid atau akun sudah aktif.";
    }
} else {
    $error = "Token tidak ditemukan.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aktivasi Akun</title>
</head>
<body>
    <h2>Aktivasi Akun</h2>
    
    <?php if ($success): ?>
        <p style="color: green;"><strong>✅ Aktivasi Berhasil!</strong></p>
        <p>Nama: <?php echo htmlspecialchars($user_name); ?></p>
        <p>Email: <?php echo htmlspecialchars($user_email); ?></p>
        <hr>
        <p><a href="login.php">Login Sekarang</a></p>
    <?php else: ?>
        <p style="color: red;"><strong>❌ ERROR:</strong> <?php echo $error; ?></p>
        <hr>
        <p><a href="login.php">Login</a> | <a href="register.php">Daftar Ulang</a></p>
    <?php endif; ?>
</body>
</html>