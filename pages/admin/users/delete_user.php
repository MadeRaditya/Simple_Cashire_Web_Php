<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM users WHERE id = $id";

        if(mysqli_query($conn, $sql)) {
            echo "User berhasil dihapus!";
            header("Location:users.php");
        }else{
            echo "Terjadi kesalahan: " . mysqli_error($conn);
        }
    }
?>