<?php
include('../include/config.php');

// Ensure JSON response
header('Content-Type: application/json');
// Fetch counts
$sql = "SELECT 
            (SELECT COUNT(*) FROM students) AS students_count,
            (SELECT COUNT(*) FROM events) AS events_count,
            (SELECT COUNT(*) FROM users) AS users_count";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

echo json_encode($data); // Return JSON response

$conn->close();
?>
