<?php
session_start(); 

$admin_username = 'admin';
$admin_password = 'admin123'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $entered_username = $_POST['username'];
        $entered_password = $_POST['password'];

        // Validate credentials
        if ($entered_username === $admin_username && $entered_password === $admin_password) {
            $_SESSION['is_admin'] = true; // Set session variable to indicate admin login
            header('Location: admin.php?action=dashboard');
            exit();
        } else {
            echo '<p style="color:red;">Invalid username or password.</p>';
        }
    } else {
        echo '<p style="color:red;">Username and password are required.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
        }

        .return-button {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Admin Login</h1>
    <form action="admin-login.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
    <div class="return-button">
        <a href="login.html">Return to Login</a>
    </div>
</div>
</body>
</html>


