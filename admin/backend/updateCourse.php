<?php
include('../include/config.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim($_POST['id']);
    $course_name = trim($_POST['course_name']);

    // Validate input
    if (empty($id) || empty($course_name)) {
        echo json_encode(["success" => false, "error" => "Invalid input."]);
        exit;
    }

    // Check if the course exists
    $stmt = $conn->prepare("SELECT name FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["success" => false, "error" => "Course not found."]);
        exit;
    }

    $stmt->bind_result($existingName);
    $stmt->fetch();
    $stmt->close();

    // Check if the new name is the same as the old one
    if ($course_name === $existingName) {
        echo json_encode(["success" => false, "error" => "No changes detected."]);
        exit;
    }

    // Update the course name
    $stmt = $conn->prepare("UPDATE course SET name = ? WHERE course_id = ?");
    $stmt->bind_param("si", $course_name, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Course updated successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => "Error updating course: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
