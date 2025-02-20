<?php
include('../include/config.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim($_POST['id']);
    $year_level = trim($_POST['year_level']);

    // Validate input
    if (empty($id) || empty($year_level)) {
        echo json_encode(["success" => false, "error" => "Invalid input."]);
        exit;
    }

    // Check if the year exists
    $stmt = $conn->prepare("SELECT level FROM year_level WHERE year_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["success" => false, "error" => "Year not found."]);
        exit;
    }

    $stmt->bind_result($existingLevel);
    $stmt->fetch();
    $stmt->close();

    // Check if the new value is the same as the old one
    if ($year_level === $existingLevel) {
        echo json_encode(["success" => false, "error" => "No changes detected."]);
        exit;
    }

    // Update the year level
    $stmt = $conn->prepare("UPDATE year_level SET level = ? WHERE year_id = ?");
    $stmt->bind_param("si", $year_level, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Year updated successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => "Error updating year: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
