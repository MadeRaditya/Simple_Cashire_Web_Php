<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkRole($roles, $redirect_to ='../login.php' ) {
    if (!isLoggedIn()) {
        header('Location:' . $redirect_to);
        exit();
    }

    if(!is_array($roles)){
        $roles = array($roles);
    }

    if (!in_array($_SESSION['role'], $roles)) {
        echo "Akses Ditolak! Anda tidak memiliki hak akses untuk halaman ini.";
        exit();
    }
}
?>
