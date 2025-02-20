<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

// Include database configuration
include('../include/config.php');

// Fetch POST data
$event_id = $_POST['event_id'] ?? null;
$student_id = $_POST['student_id'] ?? null;
$attendance = $_POST['attendance'] ?? null;
$time_now = date('Y-m-d H:i:s'); // Current timestamp
$datenow = date('Y-m-d'); // Current date

if ($event_id && $student_id && $attendance) {
    // Determine the field to update
    $field = ($attendance === 'timein') ? 'timein' : 'timeout';
    
    // Prepare SQL query
    $query = "UPDATE `attendance` SET `$field` = ? WHERE `event_id` = ? AND `student_id` = ? AND `date` = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("siss", $time_now, $event_id, $student_id, $datenow);
        $stmt->execute();

        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Attendance updated successfully.',
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No records were updated. Please check the input data.',
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare the statement.',
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input data. event_id, student_id, and attendance are required.',
    ]);
}

$conn->close();
?>
