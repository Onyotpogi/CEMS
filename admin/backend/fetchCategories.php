<?php
include('../include/config.php');

// Set header to JSON for AJAX response
header('Content-Type: application/json');

// Define the query to fetch categories
$sql = "SELECT category_id AS id, type AS category_name FROM categories ORDER BY type ASC";

$result = $conn->query($sql);

$categories = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Return the data as JSON
echo json_encode($categories);

$conn->close();
?>
