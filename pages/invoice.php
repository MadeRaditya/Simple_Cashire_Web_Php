<?php
include('../includes/db.php');
include('../includes/auth.php');

checkRole('kasir');

if (!isset($_GET['order_id'])) {
    echo "Order ID tidak ditemukan.";
    exit;
}

$order_id = $_GET['order_id'];

$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT o.*, t.table_number, u.username 
    FROM orders o
    LEFT JOIN tables t ON o.table_id = t.id
    JOIN users u ON o.user_id = u.id
    WHERE o.id = '$order_id'"));


$items = mysqli_query($conn, "SELECT oi.*, m.name 
    FROM order_items oi
    JOIN menu_items m ON oi.menu_item_id = m.id
    WHERE oi.order_id = '$order_id'");

$payment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM payments WHERE order_id = '$order_id'"));

if (!$order || !$payment) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $order_id ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #fff; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
        }
        h2, h3 { text-align: center; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .total-row {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
        }
        .back-btn{
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 12px;
            background-color:rgb(4, 129, 0);
            color: white;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
        }

        @media print {
            .print-btn { display: none; }
            .back-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>INVOICE</h2>
        <p><strong>No Invoice:</strong> <?= $order_id ?></p>
        <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
        <p><strong>Kasir:</strong> <?= $order['username'] ?></p>
        <p><strong>Meja:</strong> <?= $order['table_number'] ?? 'Take Away' ?></p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= $item['quantity'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Dibayar</td>
                    <td class="text-right">Rp <?= number_format($payment['amount_paid'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Kembalian</td>
                    <td class="text-right">Rp <?= number_format($payment['change_given'], 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <a class="print-btn" href="#" onclick="window.print(); return false;">🖨️ Cetak Invoice</a>

        <a class='back-btn' href="order_list.php">List Order</a>
    </div>
</body>
</html>
