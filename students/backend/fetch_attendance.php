
<?php
include('../include/config.php');

// Ensure JSON response
header('Content-Type: application/json');
$conn->set_charset("utf8");

// Fetch inputs
$studentId = $_POST['studentId'] ?? '';
$event_id = $_POST['event_id'] ?? '';
$page = $_POST['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Main query with studentId filter
$query = "SELECT s.first_name, yl.level, s.last_name, c.name AS course, s.year, a.timein, a.timeout, e.title 
          FROM attendance AS a
          INNER JOIN events AS e ON a.event_id = e.event_id
          LEFT JOIN students AS s ON a.student_id = s.student_id
          LEFT JOIN course AS c ON s.course = c.course_id 
          INNER JOIN year_level AS yl ON s.year = yl.year_id
          WHERE (? = '' OR e.event_id = ?) 
          AND (s.student_id = ?)
          LIMIT ?, ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die(json_encode(['error' => $conn->error]));
}

$stmt->bind_param("sssii", $event_id, $event_id, $studentId, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Count total records with studentId filter
$countQuery = "SELECT COUNT(*) AS total 
               FROM attendance AS a
               INNER JOIN events AS e ON a.event_id = e.event_id
               LEFT JOIN students AS s ON a.student_id = s.student_id
               LEFT JOIN course AS c ON s.course = c.course_id
               WHERE (? = '' OR e.event_id = ?) 
               AND (? = '' OR s.student_id = ?)";

$countStmt = $conn->prepare($countQuery);
if (!$countStmt) {
    die(json_encode(['error' => $conn->error]));
}

$countStmt->bind_param("ssss", $event_id, $event_id, $studentId, $studentId);
$countStmt->execute();
$countResult = $countStmt->get_result()->fetch_assoc();
$totalRecords = $countResult['total'];

// Return data as JSON
echo json_encode([
    'data' => $data,
    'totalRecords' => $totalRecords,
    'limit' => $limit,
]);
?>
