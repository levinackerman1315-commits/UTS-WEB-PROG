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
            max-width: 480px;
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
        
        .success-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
        }
        
        .error-badge {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
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
        
        .user-email {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            color: #667eea;
            font-weight: 600;
            font-size: 14px;
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
        
        .input-wrapper {
            position: relative;
        }
        
        input {
            width: 100%;
            padding: 14px 45px 14px 14px;
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
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            user-select: none;
        }
        
        .password-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        button, .btn {
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        button:hover, .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success-message h3 {
            margin-bottom: 10px;
            font-size: 18px;
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
        }
        
        .back-link a:hover {
            color: #764ba2;
        }
        
        .expired-message {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <?php if ($success): ?>
                <!-- Success State -->
                <div class="card-header">
                    <div class="icon-badge success-badge">✓</div>
                    <h2>Password Berhasil Direset!</h2>
                    <p>Password Anda telah berhasil diubah.</p>
                </div>
                
                <div class="success-message">
                    <h3>🎉 Selamat!</h3>
                    <p>Anda sekarang dapat login dengan password baru Anda.</p>
                </div>
                
                <a href="login.php" class="btn">🚀 Login Sekarang</a>
                
            <?php elseif ($expired): ?>
                <!-- Expired Token State -->
                <div class="card-header">
                    <div class="icon-badge error-badge">⏰</div>
                    <h2>Link Kadaluarsa</h2>
                    <p>Link reset password sudah tidak berlaku.</p>
                </div>
                
                <div class="expired-message">
                    <p><strong>Link reset password hanya berlaku 1 jam.</strong></p>
                    <p style="margin-top: 10px; font-size: 13px;">Silakan lakukan permintaan reset password ulang.</p>
                </div>
                
                <a href="forgot_password.php" class="btn">🔄 Reset Ulang</a>
                
                <div class="back-link">
                    <a href="login.php">← Kembali ke Login</a>
                </div>
                
            <?php elseif (!$valid_token): ?>
                <!-- Invalid Token State -->
                <div class="card-header">
                    <div class="icon-badge error-badge">❌</div>
                    <h2>Link Tidak Valid</h2>
                    <p>Link reset password tidak ditemukan atau sudah digunakan.</p>
                </div>
                
                <div class="error-message">
                    Token reset password tidak valid atau sudah digunakan sebelumnya.
                </div>
                
                <a href="forgot_password.php" class="btn">🔄 Minta Link Baru</a>
                
                <div class="back-link">
                    <a href="login.php">← Kembali ke Login</a>
                </div>
                
            <?php else: ?>
                <!-- Reset Form State -->
                <div class="card-header">
                    <div class="icon-badge">🔑</div>
                    <h2>Buat Password Baru</h2>
                    <p>Buat password yang kuat dan mudah diingat</p>
                </div>
                
                <div class="user-email">
                    📧 <?php echo htmlspecialchars($user_email); ?>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">❌ <?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" onsubmit="return validatePassword()">
                    <div class="form-group">
                        <label>🔑 Password Baru *</label>
                        <div class="input-wrapper">
                            <input type="password" id="new_password" name="new_password" minlength="6" required autofocus>
                            <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
                        </div>
                        <p class="password-hint">Minimal 6 karakter</p>
                    </div>
                    
                    <div class="form-group">
                        <label>✅ Konfirmasi Password Baru *</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
                            <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                        </div>
                    </div>
                    
                    <button type="submit">💾 Reset Password</button>
                </form>
                
                <div class="back-link">
                    <a href="login.php">← Kembali ke Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
        }
        
        function validatePassword() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confirmPass) {
                alert('Konfirmasi password tidak cocok!');
                return false;
            }
            
            if (newPass.length < 6) {
                alert('Password minimal 6 karakter!');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>