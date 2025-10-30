<?php
// connect.php - Database Connection dengan error handling
require_once 'config.php';

// Coba konek ke database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Jika gagal, coba buat database
if (!$conn) {
    // Konek tanpa database dulu
    $temp_conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
    if (!$temp_conn) {
        die("Koneksi MySQL gagal: " . mysqli_connect_error());
    }
    
    // Buat database jika belum ada
    if (mysqli_query($temp_conn, "CREATE DATABASE IF NOT EXISTS " . DB_NAME)) {
        mysqli_select_db($temp_conn, DB_NAME);
        $conn = $temp_conn;
    } else {
        die("Gagal membuat database: " . mysqli_error($temp_conn));
    }
}

mysqli_set_charset($conn, "utf8");
?>