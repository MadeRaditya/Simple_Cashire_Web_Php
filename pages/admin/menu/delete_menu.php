<?php
include('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

$id = $_GET['id'];

$query = "SELECT * FROM menu_items WHERE id = $id";
$result = mysqli_query($conn, $query);
$menu = mysqli_fetch_assoc($result);

if (!$menu) {
    die("Menu not found.");
}

$sql = "DELETE FROM menu_items WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    if ($menu['image']) {
        unlink('../../../public/assets/img/' . $menu['image']);
    }

    header("Location: menu.php"); 
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
