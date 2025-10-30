<?php
// products.php
require_once 'connect.php';
require_once 'session.php';

requireLogin();

$user_id = getUserId();
$action = $_GET['action'] ?? 'list';
$edit_id = $_GET['id'] ?? null;

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $product_code = mysqli_real_escape_string($conn, trim($_POST['product_code']));
    $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));
    $quantity = (int)$_POST['quantity'];
    $unit = mysqli_real_escape_string($conn, trim($_POST['unit']));
    $price = (float)$_POST['price'];
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    
    $check = mysqli_query($conn, "SELECT id FROM products WHERE product_code = '$product_code' AND user_id = $user_id");
    if (mysqli_num_rows($check) > 0) {
        setFlash('error', 'Kode produk sudah digunakan!');
    } else {
        $sql = "INSERT INTO products (user_id, product_code, product_name, category, quantity, unit, price, description, created_at) 
                VALUES ($user_id, '$product_code', '$product_name', '$category', $quantity, '$unit', $price, '$description', NOW())";
        
        if (mysqli_query($conn, $sql)) {
            setFlash('success', 'Produk berhasil ditambahkan!');
            header('Location: products.php');
            exit;
        } else {
            setFlash('error', 'Gagal menambahkan produk!');
        }
    }
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $edit_id) {
    $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));
    $quantity = (int)$_POST['quantity'];
    $unit = mysqli_real_escape_string($conn, trim($_POST['unit']));
    $price = (float)$_POST['price'];
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    
    $sql = "UPDATE products SET product_name = '$product_name', category = '$category', quantity = $quantity, 
            unit = '$unit', price = $price, description = '$description', updated_at = NOW()
            WHERE id = $edit_id AND user_id = $user_id";
    
    if (mysqli_query($conn, $sql)) {
        setFlash('success', 'Produk berhasil diupdate!');
        header('Location: products.php');
        exit;
    } else {
        setFlash('error', 'Gagal mengupdate produk!');
    }
}

// DELETE
if ($action === 'delete' && $edit_id) {
    if (mysqli_query($conn, "DELETE FROM products WHERE id = $edit_id AND user_id = $user_id")) {
        setFlash('success', 'Produk berhasil dihapus!');
    }
    header('Location: products.php');
    exit;
}

// Get for edit
$edit_product = null;
if ($action === 'edit' && $edit_id) {
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id AND user_id = $user_id");
    if (mysqli_num_rows($result) > 0) {
        $edit_product = mysqli_fetch_assoc($result);
    } else {
        header('Location: products.php');
        exit;
    }
}

$products = mysqli_query($conn, "SELECT * FROM products WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk</title>
</head>
<body>
    <h1>Kelola Produk</h1>
    <p>
        <a href="dashboard.php">Dashboard</a> | 
        <a href="products.php">Produk</a> | 
        <a href="profile.php">Profil</a> | 
        <a href="logout.php">Logout</a>
    </p>
    <hr>
    
    <?php displayFlash(); ?>
    
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <h2><?php echo $action === 'add' ? 'Tambah Produk' : 'Edit Produk'; ?></h2>
        <form method="POST" action="products.php?action=<?php echo $action; ?><?php echo $edit_id ? '&id='.$edit_id : ''; ?>">
            <table>
                <tr>
                    <td>Kode Produk *</td>
                    <td><input type="text" name="product_code" value="<?php echo $edit_product['product_code'] ?? ''; ?>" <?php echo $action === 'edit' ? 'readonly' : ''; ?> required></td>
                </tr>
                <tr>
                    <td>Nama Produk *</td>
                    <td><input type="text" name="product_name" value="<?php echo $edit_product['product_name'] ?? ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Kategori *</td>
                    <td><input type="text" name="category" value="<?php echo $edit_product['category'] ?? ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Jumlah *</td>
                    <td><input type="number" name="quantity" value="<?php echo $edit_product['quantity'] ?? ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Satuan *</td>
                    <td><input type="text" name="unit" value="<?php echo $edit_product['unit'] ?? ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Harga *</td>
                    <td><input type="number" name="price" value="<?php echo $edit_product['price'] ?? ''; ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td><textarea name="description" rows="4" cols="40"><?php echo $edit_product['description'] ?? ''; ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit">Simpan</button>
                        <a href="products.php">Batal</a>
                    </td>
                </tr>
            </table>
        </form>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
        <h2>Daftar Produk</h2>
        <p><a href="products.php?action=add">Tambah Produk</a></p>
        
        <?php if (mysqli_num_rows($products) > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($p = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['product_code']); ?></td>
                    <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($p['category']); ?></td>
                    <td><?php echo $p['quantity']; ?> <?php echo htmlspecialchars($p['unit']); ?></td>
                    <td>Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                    <td>
                        <a href="products.php?action=edit&id=<?php echo $p['id']; ?>">Edit</a> |
                        <a href="products.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Belum ada produk.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>