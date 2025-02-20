<?php
include '../include/config.php'; // Ensure correct database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Log request ID
    file_put_contents("debug.log", "ID received: " . $id . "\n", FILE_APPEND);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid ID."]);
        exit;
    }

    // Check if the record exists
    $checkQuery = "SELECT * FROM students WHERE student_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Record not found."]);
        exit;
    }

    // Proceed with deletion
    $query = "DELETE FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
