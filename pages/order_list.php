<?php
include('../includes/db.php'); 
include('../includes/auth.php'); 

checkRole(['kasir', 'pelayan']);

$query = "SELECT o.*, u.username AS user_name, t.table_number 
          FROM orders o
          LEFT JOIN users u ON o.user_id = u.id
          LEFT JOIN tables t ON o.table_id = t.id
          WHERE o.status != 'completed' AND o.status != 'cancelled'
          ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn)); 
}

if (isset($_GET['action']) && $_GET['action'] == 'pay' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    header("Location: payment.php?order_id=$order_id");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $cancel_query = "UPDATE orders SET status = 'cancelled' WHERE id = '$order_id' AND status = 'pending'";

    if (mysqli_query($conn, $cancel_query)) {
        echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location.href = 'order_list.php';</script>";
    } else {
        echo "Error cancelling order: " . mysqli_error($conn);
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    header("Location: edit_order.php?order_id=$order_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
       
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #007BFF;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 200px;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            outline: none;
            border-color: #007BFF;
        }

        .table-container {
            overflow-x: auto; 
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #fff;
            transition: all 0.3s ease;
            word-wrap: break-word;
        }

        table th {
            background-color: #007BFF;
            color: white;
            font-weight: 500;
        }

        table td a {
            color: #007BFF;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        table td a:hover {
            background-color: #e0e0e0;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-buttons a {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .action-buttons a.cancel {
            background-color: #dc3545;
        }

        .action-buttons a.edit {
            background-color: #ffc107;
        }

        .action-buttons a:hover {
            background-color: #218838;
        }

        .action-buttons a.cancel:hover {
            background-color: #c82333;
        }

        .action-buttons a.edit:hover {
            background-color: #e0a800;
        }

        .no-orders {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
        }

        
        @media (max-width: 768px) {
            .table-container {
                padding-bottom: 15px;
            }

            table th, table td {
                padding: 10px;
                font-size: 0.9rem;
            }

            .search-container input {
                width: 150px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 8px;
                width: 100%;
            }

            .action-buttons a {
                width: 50%;
                text-align: center;
                padding: 10px;
            }

          
            table th:nth-child(7), table td:nth-child(7) {
                display: none;
            }

            table th:nth-child(8), table td:nth-child(8) {
                width: 10%;
                overflow: hidden;
            }
        }

   
        @media (max-width: 768px) and (min-width: 601px) {
            .container {
                width: 95%; 
                padding: 15px;
            }

            table th, table td {
                font-size: 0.7rem; 
            }

            .search-container input {
                width: 180px;
            }

            .action-buttons a {
                font-size: 0.5rem; 
            }

            .no-orders {
                font-size: 1.1rem; 
            }

            table th:nth-child(8), table td:nth-child(8) {
                overflow: hidden;
            }
        }

        @media (max-width: 480px) {
            table th, table td {
                font-size: 0.8rem; 
                padding: 8px;
            }

            .search-container input {
                width: 120px;
                font-size: 0.9rem;
            }

            .container {
                width: 100%; 
                padding: 10px;
            }

            .action-buttons a {
                padding: 8px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 360px) {
            table th, table td {
                font-size: 0.75rem; 
                padding: 6px;
            }

            .search-container input {
                width: 100px; 
                font-size: 0.8rem;
            }

            .container {
                padding: 8px; 
            }

            .action-buttons a {
                padding: 6px 8px;
                font-size: 0.75rem;
            }
        }

    </style>
</head>
<body>
    <div class="container">

        <?php
            require('../includes/header.php');
        ?>
        <h1>Daftar Pesanan</h1>

        <div class="search-container">
            <input type="text" placeholder="Cari pesanan..." id="searchInput" onkeyup="searchOrders()">
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table id="orderTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Order dari</th>
                            <th>Meja</th>
                            <th>Tipe Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Waktu Pemesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['user_name']; ?></td>
                                <td><?php echo $order['table_number'] ? $order['table_number'] : 'Take-away'; ?></td>
                                <td><?php echo ucfirst($order['order_type']); ?></td>
                                <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                <td><?php echo ucfirst($order['status']); ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                                <td>
                                    <?php if ($order['status'] == 'pending'): ?>
                                        <div class="action-buttons">
                                            <?php if ($_SESSION['role'] == 'kasir' || $_SESSION['role'] == 'admin'): ?>
                                                <a href="order_list.php?action=pay&order_id=<?php echo $order['id']; ?>">Bayar</a>
                                                <a href="order_list.php?action=cancel&order_id=<?php echo $order['id']; ?>" class="cancel">Batalkan</a>
                                            <?php endif; ?>
                                            <a href="order_list.php?action=edit&order_id=<?php echo $order['id']; ?>" class="edit">Edit</a>
                                        </div>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-orders">Tidak ada pesanan yang tersedia.</p>
        <?php endif; ?>

        <?php
            require('../includes/footer.php');
        ?>
        
    </div>

    <script>
        function searchOrders() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('orderTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        let textValue = cells[j].textContent || cells[j].innerText;
                        if (textValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
