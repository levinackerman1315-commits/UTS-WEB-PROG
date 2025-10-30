<?php
// profile.php
require_once 'connect.php';
require_once 'session.php';

requireLogin();

$user_id = getUserId();
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    
    if (mysqli_query($conn, "UPDATE users SET full_name = '$full_name', phone = '$phone', updated_at = NOW() WHERE id = $user_id")) {
        $_SESSION['user_name'] = $full_name;
        setFlash('success', 'Profil berhasil diupdate!');
        header('Location: profile.php');
        exit;
    } else {
        setFlash('error', 'Gagal mengupdate profil!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
</head>
<body>
    <h1>Profil Pengguna</h1>
    
    <nav style="margin-bottom: 20px;">
        <a href="dashboard.php">Dashboard</a> | 
        <a href="products.php">Produk</a> | 
        <a href="profile.php">Profil</a> | 
        <a href="change_password.php">Ubah Password</a> | 
        <a href="logout.php">Logout</a>
    </nav>
    
    <hr>
    
    <?php displayFlash(); ?>
    
    <h2>Informasi Profil</h2>
    <div style="background: #f9f9f9; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <p><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($user['phone'] ?? '-'); ?></p>
        <p><strong>Status Akun:</strong> <?php echo $user['status'] === 'ACTIVE' ? 'Aktif' : $user['status']; ?></p>
        <p><strong>Terdaftar:</strong> <?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></p>
        <?php if ($user['updated_at']): ?>
            <p><strong>Terakhir Update:</strong> <?php echo date('d M Y H:i', strtotime($user['updated_at'])); ?></p>
        <?php endif; ?>
    </div>
    
    <h2>Edit Profil</h2>
    <form method="POST">
        <table style="margin-bottom: 20px;">
            <tr>
                <td style="padding: 5px;">Email</td>
                <td style="padding: 5px;"><input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled></td>
            </tr>
            <tr>
                <td style="padding: 5px;">Nama Lengkap *</td>
                <td style="padding: 5px;"><input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required></td>
            </tr>
            <tr>
                <td style="padding: 5px;">Telepon</td>
                <td style="padding: 5px;"><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"></td>
            </tr>
            <tr>
                <td style="padding: 5px;"></td>
                <td style="padding: 5px;">
                    <button type="submit">Simpan Perubahan</button>
                    <a href="dashboard.php" style="margin-left: 10px;">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>