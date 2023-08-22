<?php
session_start(); // Start the session
if (!isset($_SESSION['UserID'])) {
    // Redirect or display an error message indicating that the user needs to log in
    header("Location: login.php"); // Redirect to login page
    exit();
}

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "test"; 

// Create a connection
$your_db_connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($your_db_connection->connect_error) {
    die("Connection failed: " . $your_db_connection->connect_error);
}

$UserID = $_SESSION['UserID']; // Assuming you're using session for user authentication
$sql = "SELECT items.* FROM items
        INNER JOIN orders ON items.ItemID = orders.ItemID
        WHERE orders.UserID = ?";
        
// Using prepared statement to avoid SQL injection
$stmt = $your_db_connection->prepare($sql);
$stmt->bind_param("i", $UserID); // Assuming UserID is an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    $selectedItems = array();
    while ($row = $result->fetch_assoc()) {
        $selectedItems[] = $row['ItemName'];
    }
} else {
    echo "Error executing the query: " . $your_db_connection->error;
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$your_db_connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Selected Items Page</title>
</head>
<body>
    <h1>Selected Items</h1>
    
    <?php if (!empty($selectedItems)): ?>
        <ul>
            <?php foreach ($selectedItems as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No items selected.</p>
    <?php endif; ?>
</body>
</html>
