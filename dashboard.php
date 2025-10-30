<?php
// dashboard.php
require_once 'connect.php';
require_once 'session.php';

requireLogin();

$user_id = getUserId();
$user_name = getUserName();

$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE user_id = $user_id"))['total'];
$total_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM products WHERE user_id = $user_id"))['total'] ?? 0;
$total_value = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity * price) as total FROM products WHERE user_id = $user_id"))['total'] ?? 0;

$recent_products = mysqli_query($conn, "SELECT * FROM products WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard Admin Gudang</h1>
    <p>
        <a href="dashboard.php">Dashboard</a> | 
        <a href="products.php">Produk</a> | 
        <a href="profile.php">Profil</a> | 
        <a href="change_password.php">Ubah Password</a> | 
        <a href="logout.php">Logout</a>
    </p>
    <hr>
    
    <?php displayFlash(); ?>
    
    <h2>Selamat Datang, <?php echo htmlspecialchars($user_name); ?>!</h2>
    
    <h3>Statistik</h3>
    <table border="1" cellpadding="10">
        <tr>
            <td><strong>Total Produk</strong></td>
            <td><?php echo number_format($total_products); ?></td>
        </tr>
        <tr>
            <td><strong>Total Stok</strong></td>
            <td><?php echo number_format($total_stock); ?></td>
        </tr>
        <tr>
            <td><strong>Nilai Inventori</strong></td>
            <td>Rp <?php echo number_format($total_value, 0, ',', '.'); ?></td>
        </tr>
    </table>
    
    <h3>Menu Cepat</h3>
    <p>
        <a href="products.php?action=add">Tambah Produk</a> | 
        <a href="products.php">Lihat Produk</a> | 
        <a href="profile.php">Edit Profil</a>
    </p>
    
    <h3>Produk Terbaru</h3>
    <?php if (mysqli_num_rows($recent_products) > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga</th>
            </tr>
            <?php while ($p = mysqli_fetch_assoc($recent_products)): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['product_code']); ?></td>
                <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                <td><?php echo htmlspecialchars($p['category']); ?></td>
                <td><?php echo $p['quantity']; ?> <?php echo htmlspecialchars($p['unit']); ?></td>
                <td>Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Belum ada produk. <a href="products.php?action=add">Tambah produk</a></p>
    <?php endif; ?>
</body>
</html>