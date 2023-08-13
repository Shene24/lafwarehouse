<?php
// Connect to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connected successfully";
}

// Define items per page and current page
$itemsPerPage = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the starting index for the results
$startIndex = ($page - 1) * $itemsPerPage;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemClass = $_POST["itemClass"];
    $itemFamily = $_POST["itemFamily"];

    $sqlCount = "SELECT COUNT(*) as total FROM sellspareparts2 WHERE 1=1";

    $sql = "SELECT StockNO, ItemClass, ItemFamily, ItemDesc, Uom, Ohqty, Locators FROM sellspareparts2 WHERE 1=1";

    if (!empty($itemClass)) {
        $sql .= " AND ItemClass = '$itemClass'";
        $sqlCount .= " AND ItemClass = '$itemClass'";
    }

    if (!empty($itemFamily)) {
        $sql .= " AND ItemFamily = '$itemFamily'";
        $sqlCount .= " AND ItemFamily = '$itemFamily'";
    }

    // Adjust the SQL query for pagination
    $sql .= " LIMIT $startIndex, $itemsPerPage";
} else {
    // If the form is not submitted, show all data
    $sqlCount = "SELECT COUNT(*) as total FROM sellspareparts2";
    $sql = "SELECT StockNO, ItemClass, ItemFamily, ItemDesc, Uom, Ohqty, Locators FROM sellspareparts2";

    // Adjust the SQL query for pagination
    $sql .= " LIMIT $startIndex, $itemsPerPage";
}

// Execute the count query to get total number of records
$countResult = $conn->query($sqlCount);
$totalRecords = $countResult->fetch_assoc()['total'];

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $itemsPerPage);

// Execute the query
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Items Data</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
         /* CSS for header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #f5f5f5;
    }
    

    .header .logo {
      font-size: 25px;
      font-family: 'Sriracha', cursive;
      color: #000;
      text-decoration: none;
      margin-left: 30px;
    }

    .nav-items {
      display: flex;
      justify-content: space-around;
      align-items: center;
      background-color: #f5f5f5;
      margin-right: 20px;
    }

    .nav-items a {
      text-decoration: none;
      color: #000;
      padding: 35px 20px;
    }
    
    /* CSS for footer */
    .footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #191825;
      padding: 40px 80px;
    }

    .footer .copy {
      color: #fff;
    }

    .bottom-links {
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 40px 0;
    }

    .bottom-links .links {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 0 40px;
    }

    .bottom-links .links span {
      font-size: 20px;
      color: #fff;
      text-transform: uppercase;
      margin: 10px 0;
    }

    .bottom-links .links a {
      text-decoration: none;
      color: #a1a1a1;
      padding: 10px 20px;
    }
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 5px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        text-decoration: none;
        color: #333;
        border-radius: 3px;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    .pagination .next {
        margin-left: 5px;
    }
    
    .item {
        border: 1px solid #ddd;
        padding: 10px;
        margin: 10px;
    }

    .select-box {
        margin-top: 5px; /* Adjust the spacing between image and checkbox */
        margin-left: 180px;
    }

    .select-button {
        width: 20px;
        height: 20px;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        position: relative; /* Required for pseudo-element positioning */
    }

    .select-button.checked {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .select-button.checked::after {
        content: '\2713'; /* Unicode character for checkmark */
        font-size: 14px;
        line-height: 1;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    </style>

<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the submitted itemClass and itemFamily values, or use default values if not set
    $selectedItemClass = isset($_POST['itemClass']) ? $_POST['itemClass'] : '';
    $selectedItemFamily = isset($_POST['itemFamily']) ? $_POST['itemFamily'] : '';
} else {
    // If the form is loaded for the first time, set default values
    $selectedItemClass = '';
    $selectedItemFamily = '';
}
?>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<body>
        <header class="header">
            <a href="index.html" class="logo">Lafarge Warehouse</a>
            <nav class="nav-items">
                <a href="index.html">Home</a>
                <!-- http://localhost/lafargeStock/db.php use this path-->
                <a href="db.php">Items</a>
                
            </nav>
        </header>
    <h2>Available Items</h2>

    <!-- HTML form -->
<form method="POST">
    <label for="itemClass">Select Item Class:</label>
    <select name="itemClass" id="itemClass">
        <option value="" <?php echo ($selectedItemClass === '') ? 'selected' : ''; ?>>All</option>
        <option value="Maintenance Spare Parts" <?php echo ($selectedItemClass === 'Maintenance Spare Parts') ? 'selected' : ''; ?>>Maintenance Spare Parts</option>
        <option value="Wear Parts, Consumable Material" <?php echo ($selectedItemClass === 'Wear Parts, Consumable Material') ? 'selected' : ''; ?>>Wear Parts, Consumable Material</option>
        <!-- Add more options for different item classes as needed -->
    </select>

    <label for="itemFamily">Select Item Family:</label>
    <select name="itemFamily" id="itemFamily">
        <option value="" <?php echo ($selectedItemFamily === '') ? 'selected' : ''; ?>>All</option>
        <option value="Electrical Materials & Equipment" <?php echo ($selectedItemFamily === 'Electrical Materials & Equipment') ? 'selected' : ''; ?>>Electrical Materials & Equipment</option>
        <option value="Semi finished Material" <?php echo ($selectedItemFamily === 'Semi finished Material') ? 'selected' : ''; ?>>Semi finished Material</option>
        <option value="Other Mechanical Parts" <?php echo ($selectedItemFamily === 'Other Mechanical Parts') ? 'selected' : ''; ?>>Other Mechanical Parts</option>
        <option value="Blowers, Compressors, Fans, Pumps" <?php echo ($selectedItemFamily === 'Blowers, Compressors, Fans, Pumps') ? 'selected' : ''; ?>>Blowers, Compressors, Fans, Pumps</option>
        <option value="Other Consumable Material" <?php echo ($selectedItemFamily === 'Other Consumable Material') ? 'selected' : ''; ?>>Other Consumable Material</option>
        <!-- Add more options for different item families as needed -->
    </select>

    <input type="submit" value="Find">
</form>


<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="item">';
        echo '<img style="width: 200px;" src="http://localhost/lafargeStock/Images/' . $row['StockNO'] . '.jpeg"/>';
        echo '<div class="select-box"><button class="select-button" data-stockno="' . $row["StockNO"] . '"></button></div>'; // Add the selection button
        echo '<h5>Description: ' . $row["ItemDesc"] . '</h5>';
        echo '<h5>Unit of Measurement: ' . $row["Uom"] . '</h5>';
        echo '<h5>On Hand Quantity: ' . $row["Ohqty"] . '</h5>';
        echo '<h5>Location: ' . $row["Locators"] . '</h5>';
        echo '<p class="selected-info"></p>'; // Add a paragraph for displaying selected information
        
        echo '</div>';
    }
    echo '<button class="redirect-button" onclick="redirectToOtherPage()">Go to Other Page with Selected Items</button>'; // Add the buttons
} else {
    echo "No data found.";
}
?>
    <!-- Pagination links -->
    
<div class= "pagination"> 
     <?php
    if ($totalPages > 1) {
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '">&laquo; Previous</a>';
        }

        // Display up to 5 pages before and after the current page
        $startPage = max(1, $page - 5);
        $endPage = min($totalPages, $page + 5);

        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($page === $i) ? 'active' : '';
            echo '<a class="' . $activeClass . '" href="?page=' . $i . '">' . $i . '</a>';
        }

        if ($page < $totalPages) {
            echo '<a class="next" href="?page=' . ($page + 1) . '">Next &raquo;</a>';
        }
    }
    ?>
    </div>
     <footer class="footer">
    <div class="copy">&copy; Lafarge Warehouse</div>
    <div class="bottom-links">
      <div class="links">
        <span>More Info</span>
        <a href="#">Home</a>
        <a href="#">Items</a>
        
      </div>
      <div class="links">
        <span>Social Links</span>
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>
  </footer>
<script>
    const selectButtons = document.querySelectorAll('.select-button');
    let selectedItems = []; // Array to store selected items

    selectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const stockNo = this.getAttribute('data-stockno');
            const itemDiv = this.closest('.item');
            const selectedInfo = itemDiv.querySelector('.selected-info');
            this.classList.toggle('checked');

            if (this.classList.contains('checked')) {
                selectedItems.push(stockNo); // Add stockNo to selectedItems array
                selectedInfo.textContent = 'This item is selected';
            } else {
                selectedItems = selectedItems.filter(item => item !== stockNo); // Remove stockNo from selectedItems array
                selectedInfo.textContent = '';
            }
        });
    });

    function redirectToOtherPage() {
        if (selectedItems.length > 0) {
            const queryString = selectedItems.map(item => 'selected[]=' + encodeURIComponent(item)).join('&');
            window.location.href = 'otherpage.php?' + queryString; // Replace 'otherpage.php' with the actual page URL
        }
    }
</script>
</body>
</html>
