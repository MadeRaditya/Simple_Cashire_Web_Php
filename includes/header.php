<?php
$role = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Kasir</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        nav {
            background-color: #007BFF;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .container-header {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        .logo {
            color: white;
            font-size: 1.75rem;
            font-weight: bold;
            text-decoration: none;
        }

        .menu-items {
            display: flex;
            gap: 1.5rem;
            justify-content: flex-end;
            flex-wrap: wrap;
            transition: all 0.3s ease;
        }

        .menu-items a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
        }

        .menu-items a:hover {
            color: #f8f9fa;
        }

        .logout {
            background-color: #dc3545;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-align: center;
        }

        .hamburger-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
        }

        .hamburger-menu div {
            width: 25px;
            height: 3px;
            background-color: white;
        }

        @media (max-width: 768px) {
            .menu-items {
                display: none;
                flex-direction: column;
                gap: 1rem;
                align-items: center;
                margin-top: 1rem;
                width: 100%;
                background-color: #007BFF;
                position: absolute;
                top: 60px;
                right: 0;
                left: 0;
                padding: 1rem 0;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                z-index: 999;
                box-sizing: border-box;
            }

            .hamburger-menu {
                display: flex;
            }

            .menu-items.show {
                display: flex;
                opacity: 1;
            }

            .menu-items a {
                font-size: 1.25rem;
                padding: 0.75rem 1.5rem;
                text-align: center;
                width: 100%;
                border-top: 1px solid #fff;
                box-sizing: border-box; 
            }

            .logout {
                font-size: 1.25rem;
                padding: 0.75rem 1.5rem;
                text-align: center;
                width: 100%;
                box-sizing: border-box; 
            }
        }

        @media (max-width: 480px) {
            .menu-items a {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }

            .logout {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="container-header">
            <a href="menu_list.php" class="logo">Restoran Kasir</a>

            <div class="hamburger-menu">
                <div></div>
                <div></div>
                <div></div>
            </div>

            <div class="menu-items">
                <a href="menu_list.php">Daftar Menu</a>
                <a href="order_list.php">Daftar Pesanan</a>
                <a href="logout.php" class="logout">Log Out</a>
            </div>
        </div>
    </nav>

    <script>
        const hamburger = document.querySelector('.hamburger-menu');
        const menuItems = document.querySelector('.menu-items');

        hamburger.addEventListener('click', () => {
            menuItems.classList.toggle('show');
        });
    </script>
</body>
</html>
