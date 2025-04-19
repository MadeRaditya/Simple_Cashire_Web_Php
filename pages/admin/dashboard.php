<?php
include_once('../../includes/db.php');
include_once('../../includes/authadmin.php');

checkRole(['admin']);

$sql_orders = "SELECT COUNT(*) AS total_orders FROM orders";
$sql_payments = "SELECT SUM(amount_paid) AS total_payments FROM payments";
$sql_meja = "SELECT COUNT(*) AS total_meja FROM tables";
$sql_users = "SELECT COUNT(*) AS total_users FROM users";

$result_orders = mysqli_query($conn, $sql_orders);
$result_payments = mysqli_query($conn, $sql_payments);
$result_meja = mysqli_query($conn, $sql_meja);
$result_users = mysqli_query($conn, $sql_users);

$orders = mysqli_fetch_assoc($result_orders);
$payments = mysqli_fetch_assoc($result_payments);
$meja = mysqli_fetch_assoc($result_meja);
$users = mysqli_fetch_assoc($result_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            min-height: 100vh;
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

        .content {
            margin-left: 280px;
            padding: 20px;
            flex: 1;
            min-width: 300px;
            transition: margin-left 0.3s ease;
        }

        .card {
            background-color: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 18px;
            color: #6c757d;
        }

        .overview {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .overview .card {
            width: calc(25% - 20px);
            min-width: 250px;
        }

        .quick-access {
            margin-top: 30px;
        }

        .quick-access button {
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-right: 15px;
            margin-bottom: 15px;
        }

        .quick-access button:hover {
            background-color: #218838;
        }

        .recent-activities {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recent-activities h3 {
            margin-bottom: 20px;
            color: #007BFF;
            font-size: 24px;
        }

        .activity-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .activity-list li {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            color: #555;
            transition: background-color 0.3s ease;
        }

        .activity-list li:hover {
            background-color: #f1f1f1;
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 767px) {
            .sidebar {
                width: 100%;
                position: fixed;
                height: 100%;
                top: 0;
                left: -100%;
                transition: left 0.3s ease;
            }

            .content {
                margin-left: 0;
                padding: 10px;
            }

            .overview .card {
                width: 48%;
                margin-bottom: 20px;
            }

            .quick-access button {
                width: 100%;
                margin-bottom: 10px;
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
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }

            .overview .card {
                width: 100%;
                margin-bottom: 20px;
            }

            .card h3 {
                font-size: 30px;
            }

            .quick-access button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="users/users.php">Users</a>
        <a href="menu/menu.php">Menu</a>
        <a href="order/orders.php">Orders</a>
        <a href="payment/payment.php">Payments</a>
        <a href="meja/meja.php">Meja</a>
        <a href="../logout.php" style="color: white; background-color: red;">Logout</a>
    </div>

    <div class="content">
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>

        <h1>Welcome to the Admin Dashboard</h1>

        <div class="overview">
            <div class="card">
                <h3><?php echo $orders['total_orders']; ?></h3>
                <p>Total Orders</p>
            </div>
            <div class="card">
                <h3>Rp <?php echo number_format($payments['total_payments'], 2); ?></h3>
                <p>Total Payments</p>
            </div>
            <div class="card">
                <h3><?php echo $meja['total_meja']; ?></h3>
                <p>Total Tables</p>
            </div>
            <div class="card">
                <h3><?php echo $users['total_users']; ?></h3>
                <p>Total Users</p>
            </div>
        </div>

        <div class="quick-access">
            <button onclick="window.location.href='meja/add_meja.php'">Add New Table</button>
            <button onclick="window.location.href='menu/add_menu.php'">Add New Menu</button>
            <button onclick="window.location.href='users/add_user.php'">Add New User</button>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
</script>

</body>
</html>
