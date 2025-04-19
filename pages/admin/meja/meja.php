<?php
include_once('../../../includes/db.php'); 
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);

$sql = 'SELECT * FROM tables';
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tables</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            display: flex;
            height: 100vh;
            flex-direction: row;
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
        .sidebar-toggle {
            display: none; 
        }


        .content {
            margin-left: 280px;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        h1 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            table-layout: auto; 
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e6f7ff;
        }

        .action-btn {
            padding: 8px 15px;
            border: none;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 30px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: #0056b3;
        }

        .action-btn.delete {
            background-color: #e74c3c;
        }

        .action-btn.delete:hover {
            background-color: #c0392b;
        }

        .action-container {
            display: flex;
            gap: 10px;
        }

        .add-table-btn {
            background-color: #28a745;
            color: white;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .add-table-btn:hover {
            background-color: #218838;
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

            

            table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            .action-btn {
                font-size: 12px; 
            }
        }
        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }
        }

    </style>
    <script>
        function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
        }

        function confirmDelete(id) {
            var confirmation = confirm("Are you sure you want to delete this table?");
            if (confirmation) {
                window.location.href = "delete_meja.php?id=" + id;
            }
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
        <a href="../order/orders.php">Orders</a>
        <a href="../payment/payment.php">Payments</a>
        <a href="meja.php">Meja</a>
        <a href="../../logout.php"  style="color: white; background-color: red; ">Logout</a>
    </div>

    <div class="content">

    <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
        <h1>Manage Tables</h1>
        <a href="add_meja.php" class="add-table-btn">Add New Table</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Table Number</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $iteration = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $iteration++; ?></td>
                        <td><?php echo $row["table_number"]; ?></td>
                        <td><?php echo $row["capacity"]; ?></td>
                        <td><?php echo ucfirst($row["status"]); ?></td>
                        <td class="action-container">
                            <a href="edit_meja.php?id=<?php echo $row["id"]; ?>" class="action-btn">Edit</a>
                            <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="action-btn delete">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
