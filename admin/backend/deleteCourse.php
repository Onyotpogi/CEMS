<?php
include('../include/config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the course ID from POST and ensure it's a valid integer.
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid course ID provided.']);
        exit;
    }

    // Optional: Check if the course exists.
    $stmt = $conn->prepare("SELECT course_id FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Course not found.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Delete the course.
    $stmt = $conn->prepare("DELETE FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error deleting course: ' . $conn->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
