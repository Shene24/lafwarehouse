<?php
// Get the selected stock numbers from the query parameters
$selectedStockNos = isset($_GET['selected']) ? $_GET['selected'] : '';
$selectedStockNos = filter_var($selectedStockNos, FILTER_SANITIZE_STRING);

// Validate and sanitize the selected stock numbers
$validStockNos = [];
if ($selectedStockNos !== '') {
    $stockNosArray = explode(',', $selectedStockNos);
    foreach ($stockNosArray as $stockNo) {
        if (preg_match('/^\d+$/', $stockNo)) {
            $validStockNos[] = $stockNo;
        }
    }
}

// Convert the PHP array to a JavaScript array
$selectedStockNosJSON = json_encode($validStockNos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Selected Items</title>
    <!-- Your head content here -->

    <script>
        // Convert the JSON-encoded array back to a JavaScript array
        const selectedStockNos = <?php echo $selectedStockNosJSON; ?>;

        // Print the selected data in the console
        console.log('Selected Stock Numbers:', selectedStockNos);
    </script>
</head>
<body>
    <!-- Your body content here -->
</body>
</html>
