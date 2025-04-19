<?php
include_once('../../../includes/db.php'); 
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    $sql = "UPDATE orders SET status='cancelled' WHERE id=$orderId";

    if (mysqli_query($conn, $sql)) {
        echo "Order has been cancelled!";
        header("Location: orders.php"); 
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
