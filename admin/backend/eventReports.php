<?php
include('../include/config.php');

// Ensure JSON response
header('Content-Type: application/json');

$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch events with ratings
$sql = "SELECT e.title, AVG(r.rating_star) AS rating, COUNT(r.student_id) AS students 
        FROM ratings AS r 
        INNER JOIN events AS e ON r.events_id = e.event_id 
        GROUP BY e.event_id 
        ORDER BY e.date_from DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Total Records
$totalRecords = $conn->query("SELECT COUNT(DISTINCT events_id) AS total FROM ratings")->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Response
echo json_encode([
    "data" => $data,
    "total_pages" => $totalPages,
    "current_page" => $page
]);
?>
