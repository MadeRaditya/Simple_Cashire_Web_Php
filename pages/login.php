<?php
    session_start();
    include_once('../includes/db.php');
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($_SESSION['role'] == 'kasir') {
            header("Location: order_list.php");
        } elseif ($_SESSION['role'] == 'pelayan') {
            header("Location: menu_list.php");
        }
    }

    $error_message = ''; 

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);

            if(password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if($user['role'] == 'admin'){
                    header('Location: ./admin/dashboard.php');
                }else if($user['role'] == 'kasir'){
                    header('Location: order_list.php');
                }else if($user['role'] == 'pelayan'){
                    header('Location: menu_list.php');
                }
                exit();
            }else{
                $error_message = "Password salah!";
            }
        }else{
            $error_message = "User tidak ditemukan!";
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        input, button {
            font-size: 16px;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="text"], input[type="password"] {
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

     
        @media (max-width: 480px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            h2 {
                font-size: 1.5em;
            }

            input, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <?php if($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
