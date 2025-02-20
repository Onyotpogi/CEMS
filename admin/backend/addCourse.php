<?php
include('../include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = $_POST['course_name'];

    $stmt = $conn->prepare("INSERT INTO course (name) VALUES (?)");
    $stmt->bind_param("s", $course_name);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}
?>
