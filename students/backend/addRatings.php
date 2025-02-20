<?php
include('../include/config.php');

header('Content-Type: application/json'); // Set header for JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';

    if ($rating > 0 && !empty($feedback) && $event_id > 0 && !empty($student_id)) {
        date_default_timezone_set('Asia/Manila'); // Set timezone to Manila
        // Check if the record already exists
        $datenow = date('Y-m-d H:i:s'); 
        $check_stmt = $conn->prepare('SELECT ratings_id FROM ratings WHERE events_id = ? AND student_id = ?');
        $check_stmt->bind_param('ss', $event_id, $student_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Record exists, update the existing record
            $update_stmt = $conn->prepare('UPDATE ratings SET  feedback= ?,  rating_star= ?, date = ? WHERE events_id = ? AND student_id = ?');
            $update_stmt->bind_param('sssss', $feedback , $rating, $datenow, $event_id, $student_id);

            if ($update_stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Rating successfully updated.' , 'feedback' => $feedback]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating rating.', 'error' => $update_stmt->error]);
            }

            $update_stmt->close();
        } else {
            // Insert new rating if it does not exist
            $insert_stmt = $conn->prepare('INSERT INTO ratings ( `student_id`, `events_id`, `feedback`, `rating_star` , `date`) VALUES (?, ?, ?, ?, ?)');
            $insert_stmt->bind_param('sssss', $student_id, $event_id, $feedback , $rating, $datenow);

            if ($insert_stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Rating successfully submitted.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error inserting rating.', 'error' => $insert_stmt->error]);
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data provided.', 'studentid:' => $student_id]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
