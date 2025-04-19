<?php
include('../includes/db.php');
include('../includes/auth.php');

checkRole(['kasir', 'pelayan']);

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $edit_query = "SELECT * FROM orders WHERE id = '$order_id' AND status = 'pending'";
    $edit_result = mysqli_query($conn, $edit_query);

    if (mysqli_num_rows($edit_result) > 0) {
        $order = mysqli_fetch_assoc($edit_result);
        $order_items_query = "SELECT oi.*, mi.name FROM order_items oi
                              JOIN menu_items mi ON oi.menu_item_id = mi.id
                              WHERE oi.order_id = '$order_id'";
        $order_items_result = mysqli_query($conn, $order_items_query);
        $order_items = mysqli_fetch_all($order_items_result, MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['item_id']) && isset($_POST['change'])) {
                $item_id = $_POST['item_id'];
                $change = $_POST['change'];
                
                $item_query = "SELECT * FROM order_items WHERE id = '$item_id' AND order_id = '$order_id'";
                $item_result = mysqli_query($conn, $item_query);
                $item = mysqli_fetch_assoc($item_result);
                
                if ($item) {
                    $new_quantity = $item['quantity'] + $change;
                    if ($new_quantity < 1) {
                        $new_quantity = 1;
                    }

                    $menu_id = $item['menu_item_id'];
                    $menu_query = "SELECT price FROM menu_items WHERE id = '$menu_id'";
                    $menu_result = mysqli_query($conn, $menu_query);
                    $menu_item = mysqli_fetch_assoc($menu_result);
                    $price = $menu_item['price'];

                    $new_total_price = $new_quantity * $price;

                    $update_query = "UPDATE order_items SET quantity = '$new_quantity', price = '$new_total_price' WHERE id = '$item_id'";
                    mysqli_query($conn, $update_query);

                    $recalculate_total_query = "SELECT SUM(price) AS total_amount FROM order_items WHERE order_id = '$order_id'";
                    $recalculate_total_result = mysqli_query($conn, $recalculate_total_query);
                    $total = mysqli_fetch_assoc($recalculate_total_result)['total_amount'];

                    $update_order_total_query = "UPDATE orders SET total_amount = '$total' WHERE id = '$order_id'";
                    mysqli_query($conn, $update_order_total_query);
                }

                echo "<script>window.location.href = window.location.href;</script>";
            }

            if (isset($_POST['menu_ids']) && isset($_POST['quantities'])) {
                $new_menu_ids = $_POST['menu_ids'];
                $new_quantities = $_POST['quantities'];

                foreach ($new_menu_ids as $index => $menu_id) {
                    $quantity = $new_quantities[$index];

                    $menu_query = "SELECT price FROM menu_items WHERE id = '$menu_id'";
                    $menu_result = mysqli_query($conn, $menu_query);
                    $menu_item = mysqli_fetch_assoc($menu_result);
                    $price = $menu_item['price'];

                    $existing_item_query = "SELECT * FROM order_items WHERE order_id = '$order_id' AND menu_item_id = '$menu_id'";
                    $existing_item_result = mysqli_query($conn, $existing_item_query);

                    if (mysqli_num_rows($existing_item_result) > 0) {
                        $existing_item = mysqli_fetch_assoc($existing_item_result);
                        $new_quantity = $existing_item['quantity'] + $quantity;
                        $new_total_price = $new_quantity * $price;

                        $update_order_item_query = "UPDATE order_items 
                                                    SET quantity = '$new_quantity', price = '$new_total_price' 
                                                    WHERE id = '{$existing_item['id']}'";
                        mysqli_query($conn, $update_order_item_query);
                    } else {
                        $total_item_price = $quantity * $price;
                        $insert_order_item_query = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
                                                    VALUES ('$order_id', '$menu_id', '$quantity', '$total_item_price')";
                        mysqli_query($conn, $insert_order_item_query);
                    }
                }

                $recalculate_total_query = "SELECT SUM(price) AS total_amount FROM order_items WHERE order_id = '$order_id'";
                $recalculate_total_result = mysqli_query($conn, $recalculate_total_query);
                $total = mysqli_fetch_assoc($recalculate_total_result)['total_amount'];

                $update_order_total_query = "UPDATE orders SET total_amount = '$total' WHERE id = '$order_id'";
                mysqli_query($conn, $update_order_total_query);

                echo "<script>alert('Pesanan berhasil diperbarui.'); window.location.href = 'order_list.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Pesanan tidak ditemukan atau sudah tidak dapat diedit.'); window.location.href = 'order_list.php';</script>";
    }
} else {
    echo "<script>alert('Pesanan tidak ditemukan.'); window.location.href = 'order_list.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 20px;
            color: #666;
            margin-bottom: 15px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .menu-item button {
            padding: 5px 15px;
            background-color: #ddd;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .menu-item button:hover {
            background-color: #bbb;
        }

        .menu-item span {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .menu-item-price {
            color: #4e73df;
            font-weight: 600;
            font-size: 18px;
        }

        .form-container select,
        .form-container input {
            padding: 10px;
            border: 1px solid #d3d9df;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            background-color: #fafbfc;
        }

        .form-container button {
            padding: 15px;
            background-color: #4e73df;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #2e59d9;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #4e73df;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Pesanan #<?php echo $order['id']; ?></h1>

        <h3>Menu Items:</h3>
        <?php foreach ($order_items as $item): ?>
            <div class="menu-item">
                <div>
                    <p><?php echo $item['name']; ?></p>
                    <div>
                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                        <span>x <?php echo $item['quantity']; ?></span>
                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                    </div>
                </div>
                <p class="menu-item-price"><?php echo number_format($item['price'], 2, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>

        <h3>Tambah Item Baru:</h3>
        <form method="POST" class="form-container">
            <div id="menu-inputs">
                <div>
                    <label for="menu_ids[]">Menu:</label>
                    <select name="menu_ids[]">
                        <?php
                        $menu_query = "SELECT * FROM menu_items";
                        $menu_result = mysqli_query($conn, $menu_query);
                        while ($menu = mysqli_fetch_assoc($menu_result)): ?>
                            <option value="<?php echo $menu['id']; ?>"><?php echo $menu['name']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label for="quantities[]">Jumlah:</label>
                    <input type="number" name="quantities[]" value="1" min="1">
                </div>
            </div>

            <button type="button" onclick="addMenuInput()">Tambah Menu</button>
            <button type="submit">Perbarui Pesanan</button>
        </form>

        <br>
        <a href="order_list.php">Kembali ke Daftar Pesanan</a>
    </div>

    <script>
        function addMenuInput() {
            let container = document.getElementById("menu-inputs");
            let newInput = document.createElement("div");
            newInput.innerHTML = `
                <div>
                    <label for="menu_ids[]">Menu:</label>
                    <select name="menu_ids[]">
                        <?php
                        $menu_query = "SELECT * FROM menu_items";
                        $menu_result = mysqli_query($conn, $menu_query);
                        while ($menu = mysqli_fetch_assoc($menu_result)): ?>
                            <option value="<?php echo $menu['id']; ?>"><?php echo $menu['name']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label for="quantities[]">Jumlah:</label>
                    <input type="number" name="quantities[]" value="1" min="1">
                </div>
            `;
            container.appendChild(newInput);
        }

        function updateQuantity(itemId, change) {
            let form = document.createElement("form");
            form.method = "POST";
            form.action = window.location.href;

            let itemIdInput = document.createElement("input");
            itemIdInput.type = "hidden";
            itemIdInput.name = "item_id";
            itemIdInput.value = itemId;

            let changeInput = document.createElement("input");
            changeInput.type = "hidden";
            changeInput.name = "change";
            changeInput.value = change;

            form.appendChild(itemIdInput);
            form.appendChild(changeInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>

</body>
</html>
