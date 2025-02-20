<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
// Include database configuration
include('../include/config.php');

// Fetch POST data
$event_id = $_POST['event_id'] ?? null;
$student_id = $_POST['student_id'] ?? null;
$attendance = $_POST['attendance'] ?? null;
$time_now = date('Y-m-d H:i:s'); // Current timestamp for `timein`

if ($event_id && $student_id) {
    // Query to update attendance
    if($_POST['attendance'] == 'timein'){
        $query = "UPDATE `attendance` SET `timein`= ? WHERE `event_id` = ? AND `student_id` = ?";
    }else{
        $query = "UPDATE `attendance` SET `timeout`= ? WHERE `event_id` = ? AND `student_id` = ?";
    }
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sii", $time_now, $event_id, $student_id);
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
        'message' => 'Invalid input data. Both event_id and student_id are required.',
    ]);
}

$conn->close();
?>
