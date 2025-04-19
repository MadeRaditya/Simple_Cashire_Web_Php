<?php
    session_start();
    include_once('../includes/db.php');

    $error_message = ''; 
    $success_message = ''; 

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $role = $_POST['role'];

        if(empty($username)) {
            $error_message = "Username tidak boleh kosong!";
        } elseif(strlen($username) < 3 || strlen($username) > 20) {
            $error_message = "Username harus antara 3 hingga 20 karakter!";
        } elseif(!preg_match("/^[a-zA-Z0-9\s_-]*$/", $username)) {
            $error_message = "Username hanya boleh mengandung huruf, angka, spasi, garis bawah, dan tanda hubung!";
        }


        if(empty($password)) {
            $error_message = "Password tidak boleh kosong!";
        } elseif(strlen($password) < 6) {
            $error_message = "Password harus terdiri dari minimal 6 karakter!";
        }

        if(empty($role)) {
            $error_message = "Pilih peran pengguna!";
        }

        if(empty($error_message)){
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0){
                $error_message = "Username sudah terdaftar!";
            } else {
                $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
                if(mysqli_query($conn, $query)){
                    $success_message = "Registrasi berhasil! Silahkan login.";
                } else {
                    $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #1e3a8a; 
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, select, button {
            font-size: 16px;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="text"], input[type="password"] {
            background-color: #f9fafb;
        }

        select {
            background-color: #f9fafb;
        }

        button {
            background-color: #1e3a8a; 
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2563eb;
        }

        p {
            text-align: center;
            margin-top: 10px;
        }

        a {
            color: #1e3a8a;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            h2 {
                font-size: 1.5em;
            }

            input, select, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Pengguna Baru</h2>
        
        <?php if($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="role" required>
                <option value="admin" <?php echo (isset($role) && $role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="kasir" <?php echo (isset($role) && $role == 'kasir') ? 'selected' : ''; ?>>Kasir</option>
                <option value="pelayan" <?php echo (isset($role) && $role == 'pelayan') ? 'selected' : ''; ?>>Pelayan</option>
            </select><br>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
