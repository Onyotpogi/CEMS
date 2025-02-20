<?php
include('../include/config.php');

// Ensure JSON response
header('Content-Type: application/json');

$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch paginated attendance records
$sql = "SELECT 
            title, 
            COUNT(CASE WHEN timein IS NOT NULL AND timein != '' THEN 1 END) AS timein_count, 
            COUNT(CASE WHEN timeout IS NOT NULL AND timeout != '' THEN 1 END) AS timeout_count 
        FROM attendance AS a 
        INNER JOIN events AS e ON a.event_id = e.event_id 
        GROUP BY a.event_id
        ORDER BY a.event_id DESC 
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Total Records (Ensure correct counting for pagination)
$totalRecordsQuery = "SELECT COUNT(DISTINCT event_id) AS total FROM attendance";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Response
echo json_encode([
    "data" => $data,
    "total_pages" => $totalPages,
    "current_page" => $page
]);
?>
