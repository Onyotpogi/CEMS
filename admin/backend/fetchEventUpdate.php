<?php
// Include database connection
include('../include/config.php');

// Check if the event ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $eventId = $_GET['id'];

    // Query to get event data based on event ID
    $sql = "SELECT `event_id`, `category_id`, `image`, `title`, `description`, `date_from`, `date_to`, `attendance_status` FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event exists
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        // Return event data as JSON
        echo json_encode($event );
    } else {
        // If no event found, return an error message
        echo json_encode(['error' => 'Event not found']);
    }
} else {
    // If no event ID is provided or it's invalid, return an error
    echo json_encode(['error' => 'Invalid event ID']);
}

$stmt->close();
$conn->close();
?>
