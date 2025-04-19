<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);
$error = '';
$success = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];

        $maxFileSize = 5 * 1024 * 1024; 
        if ($fileSize > $maxFileSize) {
            $error = 'File terlalu besar. Maksimal ukuran file adalah 5MB.';
        }

        $allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
        $extension = null;

        if ($fileType == 'image/jpeg') {
            $extension = '.jpg';
        } elseif ($fileType == 'image/png') {
            $extension = '.png';
        } elseif ($fileType == 'image/jpg') {
            $extension = '.jpg';
        }

        if (!$extension) {
            $error = 'Format file tidak diperbolehkan. Harus berformat JPEG, JPG, atau PNG.';
        }

        if (empty($error)) {
            $uploadDir = '../../../public/assets/img/';
            $newFileName = $name . $extension; 
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $image = $newFileName;
            } else {
                $error = 'Terjadi kesalahan saat mengupload file.';
            }
        }
    } else {
        $error = 'Tidak ada file yang diupload atau file error.';
    }

    if (empty($error)) {
        $sql = "INSERT INTO menu_items (name, category, price, description, image) 
                VALUES ('$name', '$category', '$price', '$description', '$image')";

        if (mysqli_query($conn, $sql)) {
            $success = "Menu item added successfully.";
            header("Location: menu.php"); 
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
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
            padding: 40px 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #007BFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
        }

        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        input[type="file"] {
            padding: 5px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            font-size: 18px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <h1>Add New Menu Item</h1>
    
    <?php if ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="add_menu.php" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="food">Food</option>
            <option value="beverage">Beverage</option>
            <option value="dessert">Dessert</option>
        </select>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" placeholder="Enter description..."></textarea>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image">

        <input type="submit" name="submit" value="Add Menu Item">
    </form>

    <a href="menu.php" style="color: #007BFF; text-decoration: none;">Back to Menu List</a>
</body>
</html>
