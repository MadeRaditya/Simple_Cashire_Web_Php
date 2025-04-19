<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);
$id = $_GET['id'];

$sql = "SELECT * FROM tables WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table_number = $_POST['table_number'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    $sql = "UPDATE tables SET table_number='$table_number', capacity='$capacity', status='$status' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Table updated successfully!";
        header("Location: meja.php"); 
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Table</title>
    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: 20px;
        }

        h1 {
            color: #007BFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            margin: 10px;
            display: inline-block;
            text-align: center;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 0 auto;
            transition: transform 0.3s ease;
        }

        form:hover {
            transform: scale(1.03);
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            form {
                padding: 20px;
                width: 90%;
            }

            button {
                padding: 12px;
            }

            a {
                font-size: 14px;
            }
        }

    </style>
</head>
<body>
    <h1>Edit Table</h1>

    <form method="POST" action="">
        <label for="table_number">Table Number:</label>
        <input type="text" name="table_number" value="<?php echo $row['table_number']; ?>" required>

        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" value="<?php echo $row['capacity']; ?>" required>

        <label for="status">Status:</label>
        <select name="status" required>
            <option value="available" <?php echo ($row['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
            <option value="occupied" <?php echo ($row['status'] == 'occupied') ? 'selected' : ''; ?>>Occupied</option>
        </select>

        <button type="submit">Update Table</button>
    </form>

    <footer>
        <a href="meja.php">Back to Tables List</a>
    </footer>
</body>
</html>
