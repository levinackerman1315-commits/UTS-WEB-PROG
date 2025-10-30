<?php
// index.php
require_once 'session.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Management System</title>
</head>
<body>
    <h1>User Management System</h1>
    <p>Sistem Manajemen Admin Gudang</p>
    
    <h3>Fitur Utama:</h3>
    <ul>
        <li>Registrasi & Aktivasi Akun via Email</li>
        <li>Login dengan Email & Password</li>
        <li>Dashboard Admin Gudang</li>
        <li>CRUD Produk (Create, Read, Update, Delete)</li>
        <li>Manajemen Profil Pengguna</li>
        <li>Ubah Password & Reset Password</li>
    </ul>
    
    <hr>
    <p>
        <a href="login.php">Login</a> | 
        <a href="register.php">Daftar Sekarang</a>
    </p>
</body>
</html>