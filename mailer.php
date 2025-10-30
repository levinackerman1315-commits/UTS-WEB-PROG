<?php
// mailer.php - PHPMailer Integration
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'config.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

function sendActivationEmail($email, $activation_token, $name) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Aktivasi Akun - User Management System';

        $activation_link = SITE_URL . "/activate.php?token=" . $activation_token;

        $mail->Body = "
            <h2>Aktivasi Akun</h2>
            <p>Halo $name,</p>
            <p>Terima kasih telah mendaftar di User Management System.</p>
            <p>Silakan klik link berikut untuk mengaktifkan akun Anda:</p>
            <p style='margin: 30px 0;'>
                <a href='$activation_link' style='background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>Aktifkan Akun</a>
            </p>
            <p>Atau copy link berikut ke browser Anda:</p>
            <p>$activation_link</p>
            <p><strong>Link ini akan kadaluarsa jika sudah digunakan.</strong></p>
            <p>Jika Anda tidak melakukan pendaftaran ini, abaikan email ini.</p>
            <hr>
            <p style='color: #999; font-size: 12px;'>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

function sendEmail($email, $name, $subject, $body) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>