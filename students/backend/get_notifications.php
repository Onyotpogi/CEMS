<?php
include('../include/config.php');

header('Content-Type: application/json');

if (!isset($_GET['studentId']) || empty($_GET['studentId'])) {
    echo json_encode(['error' => 'Invalid or missing studentId']);
    exit;
}

$studentId = $_GET['studentId'];

// Use prepared statements to prevent SQL injection
$query = "SELECT e.event_id, title, date_from FROM attendance AS a 
          INNER JOIN events AS e ON a.event_id = e.event_id 
          WHERE status = 'unread' AND student_id = ? 
          LIMIT 5";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($notifications);
?>
