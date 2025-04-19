<?php
include('../includes/db.php');
include('../includes/auth.php');
checkRole(['kasir', 'pelayan']);
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
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #007BFF;
            margin-bottom: 2rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            padding: 1rem;
            box-sizing: border-box;
        }

        .menu-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .menu-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .details {
            padding: 1rem;
            text-align: center;
        }

        .menu-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #007BFF;
        }

        .menu-description {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .menu-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007BFF;
        }

        .menu-checkbox {
            margin-top: 1rem;
            transform: scale(1.5);
        }

        .submit-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1.2rem;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 2rem;
            box-sizing: border-box;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr 1fr;
            }

            .menu-card {
                margin: 0;
            }

            .menu-title {
                font-size: 1.2rem;
            }

            .menu-description {
                font-size: 0.9rem;
            }

            .menu-price {
                font-size: 1rem;
            }

            .submit-btn {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .menu-title {
                font-size: 1rem;
            }

            .menu-description {
                font-size: 0.8rem;
            }

            .menu-price {
                font-size: 1rem;
            }

            .submit-btn {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php
    require('../includes/header.php');
    ?>

    <div class="container">
        <h1 class="title">Daftar Menu</h1>

        <form action="order_create.php" method="GET" class="menu-form">
            <div class="menu-grid">
                <?php while ($menu = mysqli_fetch_assoc($result)): ?>
                    <div class="menu-card">
                        <img src="../public/assets/img/<?php echo $menu['image']; ?>" alt="Menu Image">
                        <div class="details">
                            <h2 class="menu-title"><?php echo $menu['name']; ?></h2>
                            <p class="menu-description"><?php echo $menu['description'] ? $menu['description'] : 'Deskripsi tidak tersedia'; ?></p>
                            <p class="menu-price"><?php echo 'Rp ' . number_format($menu['price'], 0, ',', '.'); ?></p>
                            <input type="checkbox" name="menu_ids[]" value="<?php echo $menu['id']; ?>" class="menu-checkbox">
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <button type="submit" class="submit-btn">Lanjutkan ke Pembayaran</button>
        </form>
    </div>

    <?php
    require('../includes/footer.php');
    ?>
</body>
</html>
