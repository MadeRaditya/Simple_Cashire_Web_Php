<?php
include_once('../../../includes/db.php'); 
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

$orderId = $_GET['id'];

$sql = "SELECT orders.id, orders.total_amount, orders.status, orders.user_id, users.username 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        WHERE orders.id = $orderId";
$orderResult = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($orderResult);

$itemSql = "SELECT oi.quantity, oi.price, mi.name FROM order_items oi 
            JOIN menu_items mi ON oi.menu_item_id = mi.id 
            WHERE oi.order_id = $orderId";
$itemResult = mysqli_query($conn, $itemSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: 40px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #007BFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
        }

        h2 {
            color: #007BFF;
            margin-top: 20px;
        }

        .order-details {
            margin-bottom: 30px;
            font-size: 18px;
        }

        .order-details strong {
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            table {
                font-size: 14px;
            }

            .order-details {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <h1>Order Details</h1>

    <div class="order-details">
        <p><strong>Order ID:</strong> <?php echo $order["id"]; ?></p>
        <p><strong>Total Amount:</strong> <?php echo number_format($order["total_amount"], 2); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($order["status"]); ?></p>
        <p><strong>Ordered By:</strong> <?php echo $order["username"]; ?></p> 
    </div>

    <h2>Order Items</h2>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($itemResult)): ?>
                <tr>
                    <td><?php echo $item["name"]; ?></td>
                    <td><?php echo $item["quantity"]; ?></td>
                    <td><?php echo number_format($item["price"], 2); ?></td>
                    <td><?php echo number_format($item["quantity"] * $item["price"], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="orders.php" class="back-btn">Back to Orders</a>
</body>
</html>
