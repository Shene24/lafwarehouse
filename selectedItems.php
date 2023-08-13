<?php
// Get the selected stock numbers from the query parameters
$selectedStockNos = isset($_GET['selected']) ? explode(',', $_GET['selected']) : [];

// Assuming you have a function to fetch item details based on stock number
function getItemDetails($stockNo) {
    // Implement your logic to fetch item details based on stock number
    // Return item details as needed
}

// Display the selected items
if (!empty($selectedStockNos)) {
    foreach ($selectedStockNos as $stockNo) {
        $itemDetails = getItemDetails($stockNo);
        if ($itemDetails) {
            // Display selected item details
            echo '<div class="selected-item">';
            echo '<img src="http://localhost/lafargeStock/Images/' . $stockNo . '.jpeg"/>';
            echo '<h5>Description: ' . $itemDetails["ItemDesc"] . '</h5>';
            echo '<h5>Unit of Measurement: ' . $itemDetails["Uom"] . '</h5>';
            echo '<h5>On Hand Quantity: ' . $itemDetails["Ohqty"] . '</h5>';
            echo '<h5>Location: ' . $itemDetails["Locators"] . '</h5>';
            echo '</div>';
        }
    }
} else { 
    echo 'No items selected.';
}
?>
