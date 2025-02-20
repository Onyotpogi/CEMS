<?php
include('../include/config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the year ID from POST data and ensure it's a valid integer.
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid year ID provided.']);
        exit;
    }

    // Check if the year record exists in the database.
    $stmt = $conn->prepare("SELECT year_id FROM year_level WHERE year_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Year not found.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Delete the year record.
    $stmt = $conn->prepare("DELETE FROM year_level WHERE year_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Year deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error deleting year: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
