<?php
include('../../admin/include/config.php');
header('Content-Type: application/json');
// Check if the database connection is successful
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Fetch events from the database
$sql = "SELECT `event_id`, `image`, `title`, `description`, `date_from`, `date_to`, `type` 
FROM
 `events` inner join categories on events.category_id = categories.category_id";
$result = $conn->query($sql);

$events = [];

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = [
                'id' => $row['event_id'],
                'title' => $row['title'],
                'start' => $row['date_from'],
                'end' => $row['date_to'],
                'description' => $row['description'],
                'type' => $row['type']
            ];
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed']);
    exit;
}

// Close the database connection
$conn->close();

// Return events as JSON

echo json_encode($events);
?>
