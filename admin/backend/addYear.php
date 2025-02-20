<?php
include('../include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year_name = $_POST['year_name'];

    $stmt = $conn->prepare("INSERT INTO year_level (level) VALUES (?)");
    $stmt->bind_param("s", $year_name);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}
?>
