<?php
include_once('../../../includes/db.php'); 
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

if (isset($_GET['id']) && isset($_GET['status'])) {
    $orderId = $_GET['id'];
    $newStatus = $_GET['status'];

    $sql = "UPDATE orders SET status='$newStatus' WHERE id=$orderId";

    if (mysqli_query($conn, $sql)) {
        echo "Order status updated to $newStatus!";
        header("Location: orders.php"); 
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
