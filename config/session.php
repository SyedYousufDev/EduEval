<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isStudentLoggedIn() {
    return isset($_SESSION['student_id']) && isset($_SESSION['student_email']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

function redirectIfNotStudent() {
    if (!isStudentLoggedIn()) {
        header("Location: ../student/login.php");
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdminLoggedIn()) {
        header("Location: ../admin/login.php");
        exit();
    }
}

function destroySession() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
?>