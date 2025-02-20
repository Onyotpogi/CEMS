<?php
header('Content-Type: application/json');

// Include database configuration
include('../include/config.php');

function getEventDates($conn, $event_id) {
    $query = "
        WITH RECURSIVE date_range AS (
            SELECT `date_from` AS event_date
            FROM `events`
            WHERE `event_id` = ?
            UNION
            SELECT DATE_ADD(event_date, INTERVAL 1 DAY)
            FROM date_range
            WHERE event_date < (SELECT `date_to` FROM `events` WHERE `event_id` = ?)
        )
        SELECT DATE_FORMAT(event_date, '%Y-%m-%d') AS date
        FROM date_range;
    ";

    // Prepare statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(['status' => 'error', 'message' => "SQL Error: " . $conn->error]));
    }

    // Bind parameters
    $stmt->bind_param("ii", $event_id, $event_id);

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Get current date
    date_default_timezone_set('Asia/Manila');
    $datenow = date('Y-m-d'); 

    while ($row = $result->fetch_assoc()) {
        if ($datenow === $row['date']) { 
            $insertResult = addAttendanceForm($conn, $event_id, $datenow);
            if (!$insertResult) {
                return json_encode(['status' => 'error', 'message' => 'Failed to add attendance.']);
            }
        }
    }

    // Close statement
    $stmt->close();
    return json_encode(['status' => 'success', 'message' => 'Attendance checked.']);
}


function addAttendanceForm($conn, $event_id, $date) {
    error_log("Adding attendance for date: " . $date);

    $query = "SELECT `student_id` FROM `students`";
    $result = $conn->query($query);

    if (!$result) {
        error_log("Failed to fetch students: " . $conn->error);
        return false;
    }

    while ($row = $result->fetch_assoc()) {
        $student_id = $row['student_id'];
        
        // Check if attendance already exists
        $checkQuery = "SELECT COUNT(*) AS count FROM `attendance` WHERE `event_id` = ? AND `student_id` = ? AND `date` = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        if (!$stmtCheck) {
            error_log("SQL Error: " . $conn->error);
            continue;
        }
        $stmtCheck->bind_param("iis", $event_id, $student_id, $date);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();

        if ($rowCheck['count'] == 0) { // Insert only if attendance does not exist
            error_log("Inserting attendance for student ID: " . $student_id);
            
            $insertQuery = "INSERT INTO `attendance` (`event_id`, `student_id`, `date`, `status`) VALUES (?, ?, ?, 'unread')";
            $stmt = $conn->prepare($insertQuery);
            if (!$stmt) {
                error_log("SQL Error on Insert: " . $conn->error);
                continue;
            }
            $stmt->bind_param("iis", $event_id, $student_id, $date);
            if (!$stmt->execute()) {
                error_log("Failed to insert attendance: " . $stmt->error);
                return false;
            }
        } else {
            error_log("Attendance already exists for student ID: " . $student_id);
        }
    }
    return true;
}

// Initialize response array
$response = array();

// Check if necessary parameters are provided
if (isset($_POST['action']) && isset($_POST['eventId'])) {
    $attendanceStatus = $_POST['action']; // The new attendance status
    $eventId = $_POST['eventId']; // The event ID to update

    if ($attendanceStatus === 'timein') {
        $attendanceResult = getEventDates($conn, $eventId);
        $response['attendance'] = json_decode($attendanceResult, true);
    }

    // Prepare the SQL statement to update the attendance_status
    $query = "UPDATE `events` SET `attendance_status` = ? WHERE `event_id` = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters
        $stmt->bind_param("si", $attendanceStatus, $eventId); // 's' for string, 'i' for integer

        // Execute the statement
        if ($stmt->execute()) {
            // Success response
            $response['status'] = 'success';
            $response['message'] = 'Attendance status updated successfully.';
        } else {
            // Failure response
            $response['status'] = 'error';
            $response['message'] = 'Failed to update attendance status.';
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        $response['status'] = 'error';
        $response['message'] = 'Failed to prepare SQL statement.';
    }
} else {
    // Missing parameters
    $response['status'] = 'error';
    $response['message'] = 'Invalid input data.';
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>
