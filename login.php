<?php 
require_once("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user credentials
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user["password"])) {
            // Set session variables
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["loggedin"] = true;

            // Redirect to the index.html page
            header("Location: main.html");
            exit;
        } else {
            echo "Login failed. Invalid password.";
        }
    } else {
        echo "Login failed. User not found.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:#191825;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form input[type="submit"] {
            background-color: #3c3a5a;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            width: 50%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <form class="login-form" action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Phone:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</body>
</html>

