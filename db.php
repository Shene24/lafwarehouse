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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
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
    
    <link rel="stylesheet" type="text/css" href="path/to/your/css/file.css">
</head>
<body>
    <!-- Your HTML content here -->
</body>
</html>

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
        echo '<img style="width: 200px;" src="http://localhost/lafargeStock/Images/' . $row['StockNO'] . '.jpg"/>';
        echo '<div class="select-box">Buy Item <button class="select-button" data-stockno="' . $row["StockNO"] . '"></button></div>'; // Add the selection button
        echo '<h5>Description: ' . $row["ItemDesc"] . '</h5>';
        echo '<h5>Unit of Measurement: ' . $row["Uom"] . '</h5>';
        echo '<h5>On Hand Quantity: ' . $row["Ohqty"] . '</h5>';
        echo '<h5>Location: ' . $row["Locators"] . '</h5>';
        echo '<p class="selected-info"></p>'; // Add a paragraph for displaying selected information
        
        echo '</div>';
    }
    echo '<p id="selected-count">Items selected: 0</p>'; // Echo the selected items count paragraph
    //create a buttom so it will go to the selected item page 
    echo '<button class="btn btn-primary" onclick="location.href=\'http://localhost/lafargeStock/selectedItems.php\'">Selected Items</button>';

   
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

  <?php
// Your PHP code here...


//echo '<button class="btn btn-primary" onclick="redirectToOtherPage()">Selected Items</button>'; // Echo the button
?>

<script>
    // Your JavaScript code here...

    console.log('Script started');

    const selectButtons = document.querySelectorAll('.select-button');
    let selectedItems = []; // Array to store selected items

    selectButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Log when a button is clicked
            console.log('Button clicked:', this);

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

            // Log the selected items array after each click
            console.log('Selected items:', selectedItems);
            updateSelectedCount(); // Call the function to update selected items count
        });
    });

    function redirectToOtherPage() {
        console.log('Redirecting to other page...');

        if (selectedItems.length > 0) {
            const queryString = selectedItems.map(item => 'selected[]=' + encodeURIComponent(item)).join('&');
            console.log('Query string:', queryString);

            const redirectURL = 'otherpage.php?' + queryString;
            console.log('Redirect URL:', redirectURL);

            // Check the constructed URL before redirection
            if (confirm('Redirect to ' + redirectURL + '?')) {
                window.location.href = redirectURL;
            } else {
                console.log('Redirection canceled.');
            }
        } else {
            console.log('No items selected.');
        }
    }

    function updateSelectedCount() {
        const selectedCountElement = document.getElementById('selected-count');
        selectedCountElement.textContent = 'Items selected: ' + selectedItems.length;
    }
</script>
</body>
</html>
