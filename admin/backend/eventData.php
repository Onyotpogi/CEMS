<?php
header('Content-Type: application/json');

include('../include/config.php');

// Get parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 5;
$search = isset($_GET['search']) ? $_GET['search'] : "";

// Calculate offset
$offset = ($page - 1) * $rowsPerPage;

// Search and pagination query
$sql = "SELECT events.event_id, type ,`image`, `title`, `description`, `date_from`, `date_to`, image_path FROM events inner join categories as cat on events.category_id=cat.category_id left join event_images as ei on events.event_id = ei.event_id
        WHERE title LIKE ? OR description LIKE ?  group by ei.event_id
        LIMIT ?, ? ";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Get total count for pagination
$countSql = "SELECT COUNT(*) as total FROM events WHERE title LIKE ? OR description LIKE ?";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("ss", $searchTerm, $searchTerm);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

// Return response
$response = [
    "data" => $data,
    "totalPages" => $totalPages
];

echo json_encode($response);

$conn->close();
?>
