<?php
// reset_password.php - Reset Password
require_once 'connect.php';

$token = $_GET['token'] ?? '';
$valid_token = false;
$expired = false;
$success = false;
$user_email = '';

// Validasi token
if ($token) {
    $token = mysqli_real_escape_string($conn, $token);
    $result = mysqli_query($conn, "SELECT id, email, reset_token_expiry FROM users WHERE reset_token = '$token'");
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_email = $user['email'];
        
        // Cek expired
        if (strtotime($user['reset_token_expiry']) > time()) {
            $valid_token = true;
            $user_id = $user['id'];
        } else {
            $expired = true;
        }
    }
}

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password === $confirm_password) {
        if (strlen($new_password) >= 6) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET password = '$hashed', reset_token = NULL, reset_token_expiry = NULL, updated_at = NOW() WHERE id = $user_id";
            
            if (mysqli_query($conn, $sql)) {
                $success = true;
            } else {
                $error = "Gagal mereset password. Coba lagi.";
            }
        } else {
            $error = "Password minimal 6 karakter!";
        }
    } else {
        $error = "Konfirmasi password tidak cocok!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    
    <?php if ($success): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>✅ Password Berhasil Direset!</h3>
            <p>Password Anda telah berhasil diubah.</p>
        </div>
        <p><a href="login.php">Login Sekarang</a></p>
        
    <?php elseif ($expired): ?>
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>⏰ Link Kadaluarsa</h3>
            <p>Link reset password sudah tidak berlaku.</p>
            <p>Link reset password hanya berlaku 1 jam. Silakan lakukan permintaan reset password ulang.</p>
        </div>
        <p><a href="forgot_password.php">Reset Ulang</a> | <a href="login.php">Kembali ke Login</a></p>
        
    <?php elseif (!$valid_token): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>❌ Link Tidak Valid</h3>
            <p>Link reset password tidak ditemukan atau sudah digunakan.</p>
        </div>
        <p><a href="forgot_password.php">Minta Link Baru</a> | <a href="login.php">Kembali ke Login</a></p>
        
    <?php else: ?>
        <p>Buat password baru untuk akun: <strong><?php echo htmlspecialchars($user_email); ?></strong></p>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><strong>ERROR:</strong> <?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>">
            <table style="margin-bottom: 20px;">
                <tr>
                    <td style="padding: 5px;">Password Baru *</td>
                    <td style="padding: 5px;">
                        <input type="password" name="new_password" minlength="6" required>
                        <br><small style="color: #666;">Minimal 6 karakter</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Konfirmasi Password Baru *</td>
                    <td style="padding: 5px;"><input type="password" name="confirm_password" minlength="6" required></td>
                </tr>
                <tr>
                    <td style="padding: 5px;"></td>
                    <td style="padding: 5px;"><button type="submit">Reset Password</button></td>
                </tr>
            </table>
        </form>
        
        <p><a href="login.php">Kembali ke Login</a></p>
    <?php endif; ?>
</body>
</html>