<?php
include('../includes/db.php');
include('../includes/auth.php');
checkRole(['kasir', 'pelayan']);
$menu_ids = isset($_GET['menu_ids']) ? $_GET['menu_ids'] : [];
$menus = [];
if (!empty($menu_ids)) {
    $sanitized_menu_ids = array_map(function($id) use ($conn) {
        return mysqli_real_escape_string($conn, $id);
    }, $menu_ids);

    $query = "SELECT * FROM menu_items WHERE id IN (" . implode(",", $sanitized_menu_ids) . ")";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query error: " . mysqli_error($conn));
    }
    
    while ($menu = mysqli_fetch_assoc($result)) {
        $menus[] = $menu;
    }
}
if (empty($menus)) {
    echo "<script>alert('Harap pilih menu terlebih dahulu'); window.location.href = 'menu_list.php';</script>";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah_tamu = intval($_POST['jumlah_tamu']);
    $jenis_pesanan = mysqli_real_escape_string($conn, $_POST['jenis_pesanan']);
    $table_id = null;
    if ($jenis_pesanan == 'dine-in' && isset($_POST['table_id'])) {
        $table_id = mysqli_real_escape_string($conn, $_POST['table_id']);
    }
    $total_amount = 0;
    $order_items = [];
    if (isset($_POST['menu_ids']) && is_array($_POST['menu_ids'])) {
        foreach ($_POST['menu_ids'] as $menu_id) {
            $menu_id = mysqli_real_escape_string($conn, $menu_id);
            if (isset($_POST['jumlah_porsi'][$menu_id]) && intval($_POST['jumlah_porsi'][$menu_id]) > 0) {
                $quantity = intval($_POST['jumlah_porsi'][$menu_id]);
                $menu_query = "SELECT * FROM menu_items WHERE id = '$menu_id'";
                $menu_result = mysqli_query($conn, $menu_query);
                if ($menu_item = mysqli_fetch_assoc($menu_result)) {
                    $item_total = $menu_item['price'] * $quantity;
                    $total_amount += $item_total;
                    $order_items[] = [
                        'menu_id' => $menu_id,
                        'quantity' => $quantity,
                        'price' => $menu_item['price']
                    ];
                }
            }
        }
    }
    if (empty($order_items)) {
        echo "<script>alert('Harap pilih minimal satu menu dengan jumlah yang valid'); window.history.back();</script>";
        exit();
    }
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $query_insert = "INSERT INTO orders (
        user_id, 
        table_id, 
        order_type, 
        total_amount, 
        status
    ) VALUES (
        '$user_id', 
        " . ($table_id ? "'$table_id'" : 'NULL') . ", 
        '$jenis_pesanan', 
        '$total_amount', 
        'pending'
    )";
    if (mysqli_query($conn, $query_insert)) {
        $order_id = mysqli_insert_id($conn);
        if (!empty($order_items)) {
            $order_items_query = "INSERT INTO order_items (
                order_id, 
                menu_item_id, 
                quantity, 
                price
            ) VALUES ";
            $order_items_values = [];
            foreach ($order_items as $item) {
                $order_items_values[] = "(
                    '$order_id', 
                    '{$item['menu_id']}', 
                    '{$item['quantity']}', 
                    '{$item['price']}'
                )";
            }
            $order_items_query .= implode(',', $order_items_values);
            if($jenis_pesanan == 'dine-in' && $table_id) {
                $query_update_table = "UPDATE tables SET status = 'occupied' WHERE id = '$table_id'";
                mysqli_query($conn,$query_update_table);
            }
            if (mysqli_query($conn, $order_items_query)) {
                header('Location: order_list.php');
                exit();
            } else {
                echo "Error inserting order items: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error creating order: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan Baru</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100%;
            overflow-y: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
        }
        .container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            margin-top: 20px;
            overflow-y: auto;
        }
        form {
            width: 100%;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #007BFF;
        }
        input[type="number"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="number"]:focus, select:focus {
            outline: none;
            border-color: #007BFF;
        }
        .card {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .menu-item {
            width: 48%;
            background-color: #f7f9fc;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .menu-item h4 {
            margin: 0;
            color: #333;
        }
        .menu-item p {
            color: #777;
            font-size: 14px;
        }
        .menu-item input {
            width: 50px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .select-table {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .select-table select {
            width: 70%;
        }
        .table-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            .card {
                flex-direction: column;
            }
            .menu-item {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buat Pesanan Baru</h1>
        <form action="" method="POST" onsubmit="return validateOrderForm()">
            <div>
                <label for="jumlah_tamu">Jumlah Tamu:</label>
                <input type="number" name="jumlah_tamu" required min="1">
                <label for="jenis_pesanan">Jenis Pesanan:</label>
                <select name="jenis_pesanan" id="jenis_pesanan" required onchange="toggleTableSelection()">
                    <option value="dine-in">Dine-in</option>
                    <option value="take-away">Take-away</option>
                </select>
            </div>
            <div id="table_id_field" class="select-table">
                <label for="table_id">Pilih Meja:</label>
                <select name="table_id">
                    <?php
                    $table_query = "SELECT * FROM tables WHERE status = 'available'";
                    $table_result = mysqli_query($conn, $table_query);
                    while ($table = mysqli_fetch_assoc($table_result)): ?>
                        <option value="<?php echo $table['id']; ?>"><?php echo $table['table_number']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="card">
                <?php foreach ($menus as $menu): ?>
                    <div class="menu-item">
                        <h4><?php echo $menu['name']; ?></h4>
                        <p>Rp <?php echo number_format($menu['price'], 0, ',', '.'); ?></p>
                        <input type="number" name="jumlah_porsi[<?php echo $menu['id']; ?>]" value="1" min="1" max="10">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php foreach ($menu_ids as $menu_id): ?>
                <input type="hidden" name="menu_ids[]" value="<?php echo $menu_id; ?>">
            <?php endforeach; ?>
            <button type="submit">Buat Pesanan</button>
        </form>
    </div>
    <script>
        function toggleTableSelection() {
            var jenisPesanan = document.getElementById("jenis_pesanan").value;
            var tableField = document.getElementById("table_id_field");
            tableField.style.display = (jenisPesanan === "take-away") ? "none" : "block";
        }
        function validateOrderForm() {
            var menuQuantityInputs = document.querySelectorAll('input[name^="jumlah_porsi"]');
            var hasValidQuantity = Array.from(menuQuantityInputs).some(input => parseInt(input.value) > 0);
            if (!hasValidQuantity) {
                alert('Harap pilih setidaknya satu menu');
                return false;
            }
            return true;
        }
        window.onload = function() {
            toggleTableSelection();
        };
    </script>
</body>
</html>
