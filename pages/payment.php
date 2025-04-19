<?php
include('../includes/db.php'); 
include('../includes/auth.php');

checkRole('kasir');

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query = "SELECT * FROM orders WHERE id = '$order_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query error: " . mysqli_error($conn));
    }

    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        echo "<script>alert('Pesanan tidak ditemukan.'); window.location.href = 'order_list.php';</script>";
        exit();
    }

    $total_amount = $order['total_amount']; 
    $table_number = $order['table_id']; 
} else {
    echo "<script>alert('Harap pilih pesanan terlebih dahulu.'); window.location.href = 'order_list.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount_paid = isset($_POST['amount_paid']) ? floatval($_POST['amount_paid']) : 0;
    $change_given = $amount_paid - $total_amount;

    if ($amount_paid < $total_amount) {
        echo "<script>alert('Jumlah uang yang dibayar kurang dari total pesanan.');</script>";
    } else {
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
        $query_insert_payment = "INSERT INTO payments (order_id, amount_paid, change_given, payment_method) 
                                 VALUES ('$order_id', '$amount_paid', '$change_given', '$payment_method')";

        if (mysqli_query($conn, $query_insert_payment)) {
            $query_update_order = "UPDATE orders SET status = 'completed' WHERE id = '$order_id'";

            if (mysqli_query($conn, $query_update_order)) {
                $query_update_table = "UPDATE tables SET status = 'available' WHERE id = '$table_number'";
                mysqli_query($conn, $query_update_table);
                echo "<script>alert('Pembayaran berhasil!'); window.location.href = 'invoice.php?order_id=$order_id';</script>";
            } else {
                echo "<script>alert('Gagal memperbarui status pesanan.');</script>";
            }
        } else {
            echo "<script>alert('Gagal mencatat pembayaran.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pembayaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            margin: 0;
            font-size: 2rem;
            color: #007BFF;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            font-size: 1rem;
            margin-bottom: 8px;
            font-weight: 500;
            color: #6c757d;
        }

        form input, form select {
            width: 100%;
            margin-bottom: 2rem;
            padding: 12px 14px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s ease;
        }

        form input:focus, form select:focus {
            border-color: #007BFF;
        }

        form button {
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        form button:hover {
            background-color: #0056b3;
        }

        #change_display {
            font-size: 1.2rem;
            margin-top: 20px;
            color: #333;
        }

        #change_display span {
            font-weight: bold;
            color: #e74c3c;
        }

        .alert {
            color: #e74c3c;
            font-size: 1rem;
            text-align: center;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pembayaran Pesanan #<?php echo $order_id; ?></h1>
        <p>Total Pesanan: Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></p>

        <form method="POST">
            <label for="amount_paid">Jumlah Uang yang Dibayar</label>
            <input type="number" name="amount_paid" id="amount_paid" required min="0" step="any">

            <label for="payment_method">Metode Pembayaran</label>
            <select name="payment_method" required>
                <option value="cash">Tunai</option>
                <option value="card">Kartu</option>
                <option value="other">Lainnya</option>
            </select>

            <button type="submit">Proses Pembayaran</button>
        </form>

        <h3>Detail Pembayaran</h3>
        <p id="change_display"></p>
    </div>

    <script>
        document.getElementById("amount_paid").addEventListener("input", function() {
            var amount_paid = parseFloat(this.value);
            var total_amount = <?php echo $total_amount; ?>;
            var change = amount_paid - total_amount;

            var change_display = document.getElementById("change_display");
            if (change < 0) {
                change_display.innerHTML = "Uang yang dibayar kurang dari total pesanan.";
            } else {
                change_display.innerHTML = "Kembalian: Rp " + change.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,' );
            }
        });
    </script>
</body>
</html>
