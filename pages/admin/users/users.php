<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);
$sql = 'SELECT * FROM users';

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: 0;
            margin: 0;
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
            margin-left: 260px;
            padding: 30px;
            width: 100%;
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

        td a {
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        td a:hover {
            background-color: #0056b3;
        }

        button {
            padding: 8px 16px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e04e5c;
        }

        .add-btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #218838;
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

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding: 15px;
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
                font-size: 14px;
            }

            td a, td button {
                display: block;
                margin: 5px 0;
                width: 100%;
            }

            table th, table td {
                padding: 10px;
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
    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="../dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="../menu/menu.php">Menu</a>
            <a href="../order/orders.php">Orders</a>
            <a href="../payment/payment.php">Payments</a>
            <a href="../meja/meja.php">Meja</a>
            <a href="../../logout.php" style="color: white; background-color: red;">Logout</a>
        </div>

        <div class="content">
            <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
            <h1>Manage Users</h1>

            <a href="add_user.php" class="add-btn">Add New User</a>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Member From</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $iteration = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $iteration++; ?></td>
                            <td><?php echo $row["username"]; ?></td>
                            <td><?php echo $row["role"]; ?></td>
                            <td><?php echo $row["created_at"]; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row["id"]; ?>">Edit</a> 
                                <button onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function confirmDelete(id) {
            var confirmation = confirm("Are you sure you want to delete this user?");
            if (confirmation) {
                window.location.href = "delete_user.php?id=" + id;
            }
        }
    </script>
</body>
</html>
