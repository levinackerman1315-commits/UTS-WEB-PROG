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
</head>
<body>
    <h1>Ubah Password</h1>
    
    <nav style="margin-bottom: 20px;">
        <a href="dashboard.php">Dashboard</a> | 
        <a href="products.php">Produk</a> | 
        <a href="profile.php">Profil</a> | 
        <a href="change_password.php">Ubah Password</a> | 
        <a href="logout.php">Logout</a>
    </nav>
    
    <hr>
    
    <?php displayFlash(); ?>
    
    <h2>Form Ubah Password</h2>
    
    <form method="POST" action="change_password.php">
        <table style="margin-bottom: 20px;">
            <tr>
                <td style="padding: 5px;">Password Lama *</td>
                <td style="padding: 5px;"><input type="password" name="old_password" required></td>
            </tr>
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
                <td style="padding: 5px;">
                    <button type="submit">Ubah Password</button>
                    <a href="dashboard.php" style="margin-left: 10px;">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>