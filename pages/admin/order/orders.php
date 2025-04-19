<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

$sql = 'SELECT orders.id, orders.total_amount, orders.status, orders.user_id, users.username 
        FROM orders 
        JOIN users ON orders.user_id = users.id';
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 260px;
            background-color: #007BFF;
            color: white;
            height: 100%;
            padding-top: 30px;
            position: fixed;
            transition: width 0.3s ease;
            z-index: 1000;
        }

        .sidebar a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .sidebar-toggle {
            display: none;
        }

        .content {
            margin-left: 280px;
            padding: 30px;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        h1 {
            color: #007BFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-size: 16px;
        }

        td {
            background-color: #ffffff;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e6f7ff;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .action-buttons a {
            padding: 8px 15px;
            margin-bottom: 5px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            max-width: 100%;
            box-sizing: border-box;
        }

        .details {
            background-color: #007BFF;
        }

        .complete {
            background-color: #28a745;
        }

        .cancel {
            background-color: #dc3545;
        }

        .message {
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }

        .message.success {
            color: #28a745;
        }

        .message.error {
            color: #dc3545;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
                margin: 0;
            }

            .sidebar {
                width: 100%;
                position: fixed;
                height: 100%;
                top: 0;
                left: -100%;
                transition: left 0.3s ease;
            }

            .sidebar-toggle {
                display: block;
                background-color: #007BFF;
                color: white;
                padding: 15px;
                font-size: 24px;
                cursor: pointer;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1100;
            }

            .sidebar-toggle.close {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 30px;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
            }

            h1 {
                font-size: 28px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }

            button, a {
                font-size: 10px;
            }

            td img {
                max-width: 80px;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .action-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-buttons a {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }

            table, th, td {
                font-size: 10px;
            }
        }
    </style>

    <script type="text/javascript">
        function confirmCancel(orderId) {
            var confirmation = confirm("Are you sure you want to cancel this order?");
            if (confirmation) {
                window.location.href = "cancel_order.php?id=" + orderId;
            }
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../users/users.php">Users</a>
            <a href="../menu/menu.php">Menu</a>
            <a href="orders.php">Orders</a>
            <a href="../payment/payment.php">Payments</a>
            <a href="../meja/meja.php">Meja</a>
            <a href="../../logout.php" style="color: white; background-color: red;">Logout</a>
        </div>

        <div class="content">
            <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>

            <h1>Manage Orders</h1>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Order ID</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Ordered By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $iterationNumber = 1;
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td><?php echo $iterationNumber++; ?></td>
                                <td><?php echo $row["id"]; ?></td>
                                <td><?php echo number_format($row["total_amount"], 2); ?></td>
                                <td><?php echo ucfirst($row["status"]); ?></td>
                                <td><?php echo $row["username"]; ?></td>
                                <td class="action-buttons">
                                    <?php if ($row["status"] == "pending"): ?>
                                        <a href="change_status.php?id=<?php echo $row["id"]; ?>&status=completed" class="complete">Completed</a>
                                        <a onclick="confirmCancel(<?php echo $row['id']; ?>)" class="cancel">Cancel</a>
                                    <?php else: ?>
                                        <a href="order_detail.php?id=<?php echo $row["id"]; ?>" class="details">Details</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($_GET['message'])): ?>
                <div class="message <?php echo $_GET['type'] == 'error' ? 'error' : 'success'; ?>">
                    <?php echo $_GET['message']; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
