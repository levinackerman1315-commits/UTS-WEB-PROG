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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 450px;
            width: 100%;
        }
        
        .card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .icon-badge {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .card-header h2 {
            color: #333;
            font-size: 26px;
            margin-bottom: 10px;
        }
        
        .card-header p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            animation: slideDown 0.3s ease;
        }
        
        .success-message h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .success-message p {
            font-size: 14px;
            line-height: 1.6;
        }
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: #764ba2;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .info-box p {
            color: #1976D2;
            font-size: 13px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="icon-badge"><?php echo $success ? '✉️' : '🔐'; ?></div>
                <h2><?php echo $success ? 'Email Terkirim!' : 'Lupa Password?'; ?></h2>
                <?php if (!$success): ?>
                    <p>Masukkan email Anda dan kami akan mengirimkan link untuk mereset password</p>
                <?php endif; ?>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <h3>✅ Cek Email Anda!</h3>
                    <p>Kami telah mengirimkan link untuk mereset password ke email Anda.</p>
                    <p style="margin-top: 10px;">Link akan kadaluarsa dalam <strong>1 jam</strong>.</p>
                    <p style="margin-top: 10px; font-size: 13px;">Tidak menerima email? Cek folder <strong>SPAM/Junk</strong></p>
                </div>
                <a href="login.php" style="display: block; text-align: center; padding: 14px; background: #667eea; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">Kembali ke Login</a>
            <?php else: ?>
                <?php if (isset($error)): ?>
                    <div class="error-message">❌ <?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="forgot_password.php">
                    <div class="form-group">
                        <label>📧 Email Terdaftar</label>
                        <input type="email" name="email" placeholder="contoh@email.com" required autofocus>
                    </div>
                    
                    <button type="submit">🚀 Kirim Link Reset</button>
                </form>
                
                <div class="back-link">
                    <a href="login.php">← Kembali ke Login</a>
                </div>
                
                <div class="info-box">
                    <p>💡 <strong>Catatan:</strong> Link reset password hanya berlaku 1 jam. Jika sudah kadaluarsa, Anda harus melakukan permintaan reset ulang.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>