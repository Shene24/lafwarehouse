<?php
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"]; // Retrieve selected role from form

    // Insert data into the users table
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Hash the password (you should use more secure methods)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt->bind_param("sss", $username, $hashedPassword, $role);
    
    if ($stmt->execute()) {
        // Registration successful
        // Redirect to the main page
        header("Location: main.html");
        exit(); // Make sure to exit to prevent further execution
    } else {
        // Registration failed
        echo "Registration failed. Please try again.";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    
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
        .registration-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        .registration-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registration-form input[type="text"],
        .registration-form input[type="password"],
        .registration-form select
        
        {
            width: 80%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .registration-form select {
            width: 100%; /* Adjust the width to 100% to make it a block element */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .registration-form input[type="submit"] {
            background-color: #3c3a5a;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            width: 50%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .registration-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>

 <!-- Include your CSS links here -->
</head>
<body>
    <div class="registration-container">
        <h2>User Registration</h2>
        <form class="registration-form" action="registration.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <label for="role">Role:</label>
            <select name="role" style="width: 74%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="Customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
            
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
