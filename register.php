<?php
include 'config.php';
include 'connect.php';
include 'mailer.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);

    // Validasi
    if (empty($email) || empty($password) || empty($confirm_password) || empty($full_name)) {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak sesuai!';
    } else {
        // Cek email exists
        $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($result) > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $activation_token = md5(uniqid(rand(), true));
            
            // Insert user - PASTIKAN NAMA KOLOM SESUAI
            $sql = "INSERT INTO users (email, password, full_name, phone, activation_token, status) 
                    VALUES ('$email', '$hashed_password', '$full_name', '$phone', '$activation_token', 'PENDING')";
            
            if (mysqli_query($conn, $sql)) {
                // Kirim email aktivasi
                $activation_link = SITE_URL . "/activate.php?token=" . $activation_token;

                if (sendActivationEmail($email, $activation_token, $full_name)) {
                    $success = 'Registrasi berhasil! Silakan cek email untuk aktivasi.';
                } else {
                    // Fallback untuk testing: tampilkan link aktivasi langsung
                    $success = 'Registrasi berhasil! Email gagal dikirim, tapi untuk testing, klik link berikut untuk aktivasi:<br><br>';
                    $success .= '<a href="' . $activation_link . '" style="color: blue; text-decoration: underline;">' . $activation_link . '</a><br><br>';
                    $success .= '<strong>Link ini untuk testing saja. Di production, email HARUS berfungsi.</strong>';
                }
            } else {
                $error = 'Error: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
</head>
<body>
    <h1>Registrasi Akun</h1>

    <?php if ($error): ?>
        <p style="color: red;"><strong>ERROR:</strong> <?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="post">
        <table>
            <tr>
                <td>Email *</td>
                <td><input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Password *</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td>Konfirmasi Password *</td>
                <td><input type="password" name="confirm_password" required></td>
            </tr>
            <tr>
                <td>Nama Lengkap *</td>
                <td><input type="text" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td><input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Register"></td>
            </tr>
        </table>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</body>
</html>