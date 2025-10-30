<?php
// change_password.php - Ubah Password
require_once 'connect.php';
require_once 'session.php';

requireLogin();

$user_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM users WHERE id = $user_id"));
    
    if (!password_verify($old_password, $user['password'])) {
        setFlash('error', 'Password lama tidak sesuai!');
    } elseif ($new_password !== $confirm_password) {
        setFlash('error', 'Konfirmasi password tidak cocok!');
    } elseif (strlen($new_password) < 6) {
        setFlash('error', 'Password minimal 6 karakter!');
    } else {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = '$hashed', updated_at = NOW() WHERE id = $user_id";
        
        if (mysqli_query($conn, $sql)) {
            setFlash('success', 'Password berhasil diubah!');
        } else {
            setFlash('error', 'Gagal mengubah password!');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar { 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px);
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .navbar h1 { font-size: 24px; font-weight: 600; }
        .navbar nav a { 
            color: white; 
            text-decoration: none; 
            margin-left: 20px; 
            padding: 8px 15px; 
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .navbar nav a:hover { 
            background: rgba(255,255,255,0.2); 
            transform: translateY(-2px);
        }
        
        .container { 
            max-width: 500px; 
            margin: 50px auto; 
            padding: 0 20px; 
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
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        button, .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .security-tips {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .security-tips h4 {
            color: #1976D2;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .security-tips ul {
            margin-left: 20px;
            color: #555;
            font-size: 13px;
        }
        
        .security-tips li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🔐 Ubah Password</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Produk</a>
            <a href="profile.php">Profil</a>
            <a href="change_password.php">Ubah Password</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    
    <div class="container">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?>">
                <?php echo $_SESSION['flash']['message']; unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <div class="icon-badge">🔑</div>
                <h2>Ubah Password</h2>
                <p>Buat password baru yang lebih aman</p>
            </div>
            
            <form method="POST" action="change_password.php" onsubmit="return validatePassword()">
                <div class="form-group">
                    <label>🔒 Password Lama *</label>
                    <div class="input-wrapper">
                        <input type="password" id="old_password" name="old_password" required>
                        <span class="toggle-password" onclick="togglePassword('old_password')">👁️</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>🔑 Password Baru *</label>
                    <div class="input-wrapper">
                        <input type="password" id="new_password" name="new_password" minlength="6" required>
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
                
                <div class="btn-group">
                    <button type="submit" class="btn-primary">💾 Ubah Password</button>
                    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
            
            <div class="security-tips">
                <h4>💡 Tips Password Aman:</h4>
                <ul>
                    <li>Gunakan kombinasi huruf besar, kecil, dan angka</li>
                    <li>Minimal 8 karakter untuk keamanan maksimal</li>
                    <li>Jangan gunakan informasi pribadi yang mudah ditebak</li>
                    <li>Ubah password secara berkala</li>
                </ul>
            </div>
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