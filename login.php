<?php
// login.php
require_once 'connect.php';
require_once 'session.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi!";
    } else {
        $sql = "SELECT id, email, password, full_name, status FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                if ($user['status'] === 'ACTIVE') {
                    setLogin($user['id'], $user['email'], $user['full_name']);
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = "Akun belum diaktivasi. Cek email Anda.";
                }
            } else {
                $error = "Email atau password salah!";
            }
        } else {
            $error = "Email atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    
    <?php displayFlash(); ?>
    
    <?php if ($error): ?>
        <p><strong>ERROR:</strong> <?php echo $error; ?></p>
        <hr>
    <?php endif; ?>
    
    <form method="POST">
        <table>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Login</button></td>
            </tr>
        </table>
    </form>
    
    <hr>
    <p>
        <a href="forgot_password.php">Lupa Password?</a> | 
        <a href="register.php">Daftar</a> | 
        <a href="index.php">Beranda</a>
    </p>
</body>
</html>