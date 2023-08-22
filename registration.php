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


