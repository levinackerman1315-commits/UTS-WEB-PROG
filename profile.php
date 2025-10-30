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
<html>
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>
<body>
    <h1>Profil Saya</h1>
    <p>
        <a href="dashboard.php">Dashboard</a> | 
        <a href="products.php">Produk</a> | 
        <a href="profile.php">Profil</a> | 
        <a href="change_password.php">Ubah Password</a> | 
        <a href="logout.php">Logout</a>
    </p>
    <hr>
    
    <?php displayFlash(); ?>
    
    <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Status: <?php echo $user['status']; ?></p>
    <p>Terdaftar: <?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
    
    <hr>
    <h3>Edit Profil</h3>
    <form method="POST">
        <table>
            <tr>
                <td>Email</td>
                <td><input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled></td>
            </tr>
            <tr>
                <td>Nama Lengkap *</td>
                <td><input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required></td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Simpan</button></td>
            </tr>
        </table>
    </form>
</body>
</html>