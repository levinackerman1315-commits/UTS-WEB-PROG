<?php
// create_database.php
require_once 'config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;

if (mysqli_query($conn, $sql)) {
    echo "<h2>Database '" . DB_NAME . "' berhasil dibuat!</h2>";
    echo "<p><a href='create_tables.php'>Lanjut Create Tables</a></p>";
} else {
    echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
}

mysqli_close($conn);
?>