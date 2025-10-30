<?php
// create_tables.php - PERBAIKI VERSION
require_once 'connect.php';

echo "<h2>Setup Database Tables</h2>";

// Hapus tabel jika sudah ada (optional)
// mysqli_query($conn, "DROP TABLE IF EXISTS products");
// mysqli_query($conn, "DROP TABLE IF EXISTS users");

// Table users dengan struktur tepat
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    status ENUM('PENDING', 'ACTIVE', 'INACTIVE') DEFAULT 'PENDING',
    activation_token VARCHAR(100),
    reset_token VARCHAR(100),
    reset_token_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_users)) {
    echo "<p>✅ Tabel 'users' berhasil dibuat!</p>";
} else {
    echo "<p>❌ Error users: " . mysqli_error($conn) . "</p>";
}

// Table products dengan foreign key
$sql_products = "CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_code VARCHAR(50) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    quantity INT DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    price DECIMAL(15,2) DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_code (user_id, product_code)
)";

if (mysqli_query($conn, $sql_products)) {
    echo "<p>✅ Tabel 'products' berhasil dibuat!</p>";
} else {
    echo "<p>❌ Error products: " . mysqli_error($conn) . "</p>";
}

// Cek dan insert admin
$check_admin = mysqli_query($conn, "SELECT id FROM users WHERE email = 'admin@example.com'");
if (mysqli_num_rows($check_admin) == 0) {
    $admin_pass = password_hash('admin123', PASSWORD_BCRYPT);
    $sql_admin = "INSERT INTO users (email, password, full_name, status) 
                  VALUES ('admin@example.com', '$admin_pass', 'Administrator', 'ACTIVE')";
    
    if (mysqli_query($conn, $sql_admin)) {
        echo "<p>✅ Admin default berhasil dibuat!</p>";
        echo "<p><strong>Email:</strong> admin@example.com</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
    } else {
        echo "<p>❌ Error admin: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✅ Admin sudah ada</p>";
}

echo "<hr>";
echo "<p><strong>Setup selesai!</strong></p>";
echo "<p><a href='login.php'>Login Sekarang</a></p>";

mysqli_close($conn);
?>