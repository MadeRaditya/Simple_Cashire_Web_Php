<?php
include('../../../includes/db.php'); 
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);
$query = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>
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
            transition: margin-left 0.3s ease;
        }

        .sidebar {
            width: 260px;
            background-color: #007BFF;
            color: white;
            height: 100%;
            padding-top: 30px;
            position: fixed;
            transition: left 0.3s ease;
            z-index: 1000;
            left: 0;
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
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
        }

        .add-menu-btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border-radius: 30px;
            text-align: center;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .add-menu-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            table-layout: auto; 
        }

        th, td {
            padding: 15px;
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
            height: auto;
        }

        td img {
            border-radius: 5px;
            max-width: 100px;
            height: auto;
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

        @media (max-width: 768px) {
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
                padding: 10px;
            }

            table {
                font-size: 12px;
                word-wrap: break-word;
            }

            th, td {
                padding: 8px;
            }

            .action-btn {
                font-size: 12px;
            }

            td img {
                max-width: 80px;
            }

            h1 {
                font-size: 28px;
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
            var confirmation = confirm("Are you sure you want to delete this menu?");
            if (confirmation) {
                window.location.href = "delete_menu.php?id=" + id;
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
        <a href="menu.php">Menu</a>
        <a href="../order/orders.php">Orders</a>
        <a href="../payment/payment.php">Payments</a>
        <a href="../meja/meja.php">Meja</a>
        <a href="../../logout.php" style="color: white; background-color: red; ">Logout</a>
    </div>

    <div class="content">
        <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>

        <h1>Daftar Menu</h1>

        <a href="add_menu.php" class="add-menu-btn">Add New Menu</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Categori</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $iteration = 1;
                while ($menu = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $iteration++; ?></td>
                    <td><?php echo $menu['name']; ?></td>
                    <td><?php echo $menu['category']; ?></td>
                    <td><img src="../../../public/assets/img/<?php echo $menu['image']; ?>" alt="Menu Image"></td>
                    <td><?php echo 'Rp ' . number_format($menu['price'], 0, ',', '.'); ?></td>
                    <td class="action-container">
                        <a href="edit_menu.php?id=<?php echo $menu["id"]; ?>" class="action-btn">Edit</a>
                        <button onclick="confirmDelete(<?php echo $menu['id']; ?>)" class="action-btn delete">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
