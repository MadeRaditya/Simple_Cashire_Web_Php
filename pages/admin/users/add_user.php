<?php
include_once('../../../includes/db.php');
include_once('../../../includes/authadmin2.php');

checkRole(['admin']);


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role'];

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

    if(mysqli_query($conn, $query)){
        $message = "User berhasil ditambahkan!";
        $message_type = "success";
        
        header("Location: users.php");
        exit();
    } else {
        $message = "Terjadi kesalahan: " . mysqli_error($conn);
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
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
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #007BFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
        }

        
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            margin-bottom: 40px;

        }

        label {
            font-size: 18px;
            margin-bottom: 8px;
            display: block;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #218838;
        }

       
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

       
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            form {
                padding: 15px;
            }

            h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    <h1>Add New User</h1>

    <?php if (isset($message)): ?>
        <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    
    <form method="POST" action="">
        <label for="username">Username</label>
        <input type="text" name="username" required><br>

        <label for="password">Password</label>
        <input type="password" name="password" required><br>

        <label for="role">Role</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="kasir">Kasir</option>
            <option value="pelayan">Pelayan</option>
        </select><br>

        <button type="submit">Add User</button>
    </form>
    <a href="users.php" style="color: #007BFF; text-decoration: none;">Back to User List</a>
</body>
</html>
