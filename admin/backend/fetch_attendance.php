<?php
include('../include/config.php');

// Ensure JSON response
header('Content-Type: application/json');
$conn->set_charset("utf8");

// Fetch inputs
$search = $_POST['search'] ?? '';
$event_id = $_POST['event_id'] ?? '';
$course_id = $_POST['course_id'] ?? '';
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Search query
$query = "
    SELECT a.date, s.student_id, s.first_name, yl.level, s.last_name, c.name AS course, s.year, 
           a.timein, a.timeout, e.title 
    FROM attendance AS a
    LEFT JOIN events AS e ON a.event_id = e.event_id
    LEFT JOIN students AS s ON a.student_id = s.student_id
    LEFT JOIN course AS c ON s.course = c.course_id
    LEFT JOIN year_level AS yl ON s.year = yl.year_id
    WHERE (CONCAT(s.first_name, ' ', s.last_name) LIKE ? OR s.student_id LIKE ?)
    AND (? = '' OR e.event_id = ?)
    AND (? = '' OR c.course_id = ?)
    LIMIT ?, ?
";

// Prepare statement
$stmt = $conn->prepare($query);
if (!$stmt) {
    die(json_encode(['error' => $conn->error]));
}

// Bind parameters
$searchParam = "%$search%";
$stmt->bind_param("ssssiiii", $searchParam, $searchParam, $event_id, $event_id, $course_id, $course_id, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Count query (pagination)
$countQuery = "
    SELECT COUNT(*) AS total 
    FROM attendance AS a
    LEFT JOIN events AS e ON a.event_id = e.event_id
    LEFT JOIN students AS s ON a.student_id = s.student_id
    LEFT JOIN course AS c ON s.course = c.course_id
    WHERE (CONCAT(s.first_name, ' ', s.last_name) LIKE ? OR s.student_id LIKE ?)
    AND (? = '' OR e.event_id = ?)
    AND (? = '' OR c.course_id = ?)
";

$countStmt = $conn->prepare($countQuery);
if (!$countStmt) {
    die(json_encode(['error' => $conn->error]));
}
$countStmt->bind_param("ssssii", $searchParam, $searchParam, $event_id, $event_id, $course_id, $course_id);
$countStmt->execute();
$countResult = $countStmt->get_result()->fetch_assoc();
$totalRecords = $countResult['total'];

// Return data as JSON
echo json_encode([
    'data' => $data,
    'totalRecords' => $totalRecords,
    'limit' => $limit,
    'currentPage' => $page,
    'totalPages' => ceil($totalRecords / $limit),
]);
?>
