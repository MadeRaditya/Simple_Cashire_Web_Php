<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: pages/dashboard.php");
    } elseif ($_SESSION['role'] == 'kasir') {
        header("Location: pages/order_list.php");
    } elseif ($_SESSION['role'] == 'pelayan') {
        header("Location: pages/menu_list.php");
    }
} else {
    header("Location: pages/login.php");
}
exit();

