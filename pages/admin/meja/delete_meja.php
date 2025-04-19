<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM tables WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Table deleted successfully!";
        header("Location: meja.php"); 
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
