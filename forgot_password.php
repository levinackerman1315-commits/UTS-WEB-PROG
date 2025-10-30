<?php
// forgot_password.php - Lupa Password dengan PHPMailer
require_once 'connect.php';
require_once 'mailer.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    // Cek email exists
    $result = mysqli_query($conn, "SELECT id, full_name FROM users WHERE email = '$email' AND status = 'ACTIVE'");
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate reset token
        $reset_token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Save token
        $sql = "UPDATE users SET reset_token = '$reset_token', reset_token_expiry = '$expiry' WHERE id = {$user['id']}";
        
        if (mysqli_query($conn, $sql)) {
            // Send email dengan PHPMailer
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/user-management-system/reset_password.php?token=$reset_token";
            
            $subject = "Reset Password - User Management System";
            $body = "
                <h2>Reset Password</h2>
                <p>Halo {$user['full_name']},</p>
                <p>Anda menerima email ini karena ada permintaan untuk mereset password akun Anda.</p>
                <p>Klik tombol di bawah untuk membuat password baru:</p>
                <p style='margin: 30px 0;'>
                    <a href='$reset_link' style='background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>Reset Password</a>
                </p>
                <p>Atau copy link berikut ke browser Anda:</p>
                <p>$reset_link</p>
                <p><strong>Link ini akan kadaluarsa dalam 1 jam.</strong></p>
                <p>Jika Anda tidak melakukan permintaan ini, abaikan email ini.</p>
                <hr>
                <p style='color: #999; font-size: 12px;'>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            ";
            
            if (sendEmail($email, $user['full_name'], $subject, $body)) {
                $success = true;
            } else {
                $error = "Gagal mengirim email. Coba lagi nanti.";
            }
        } else {
            $error = "Terjadi kesalahan sistem. Coba lagi nanti.";
        }
    } else {
        // Tetap tampilkan success untuk keamanan (tidak bocorkan email terdaftar atau tidak)
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
</head>
<body>
    <h2>Lupa Password</h2>
    
    <?php if ($success): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>✅ Email Terkirim!</h3>
            <p>Kami telah mengirimkan link untuk mereset password ke email Anda.</p>
            <p>Link akan kadaluarsa dalam <strong>1 jam</strong>.</p>
        </div>
        <p><a href="login.php">Kembali ke Login</a></p>
    <?php else: ?>
        <?php if (isset($error)): ?>
            <p style="color: red;"><strong>ERROR:</strong> <?php echo $error; ?></p>
        <?php endif; ?>
        
        <p>Masukkan email Anda dan kami akan mengirimkan link untuk mereset password</p>
        
        <form method="POST" action="forgot_password.php">
            <table style="margin-bottom: 20px;">
                <tr>
                    <td style="padding: 5px;">Email Terdaftar *</td>
                    <td style="padding: 5px;"><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td style="padding: 5px;"></td>
                    <td style="padding: 5px;"><button type="submit">Kirim Link Reset</button></td>
                </tr>
            </table>
        </form>
        
        <p><a href="login.php">Kembali ke Login</a></p>
    <?php endif; ?>
</body>
</html>