<?php
include('../include/config.php'); // Ensure database connection

header('Content-Type: application/json'); // Return JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'] ?? null;

    if (!$event_id) {
        echo json_encode(['status' => 'error', 'message' => 'Event ID is required.']);
        exit;
    }

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE `attendance` SET `status` = 'read' WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Attendance updated automatically.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update attendance.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
