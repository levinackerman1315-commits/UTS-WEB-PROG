<?php
// session.php - Session Management

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserEmail() {
    return $_SESSION['user_email'] ?? null;
}

function getUserName() {
    return $_SESSION['user_name'] ?? null;
}

function setLogin($user_id, $user_email, $user_name) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $user_email;
    $_SESSION['user_name'] = $user_name;
}

function logout() {
    session_destroy();
    session_start();
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function displayFlash() {
    if (isset($_SESSION['flash'])) {
        echo "<p><strong>" . strtoupper($_SESSION['flash']['type']) . ":</strong> " . $_SESSION['flash']['message'] . "</p><hr>";
        unset($_SESSION['flash']);
    }
}
?>