<?php
header('Content-Type: application/json');

// Include database configuration
include('../include/config.php');

$event_id = $_POST['event_id'] ?? null;
$student_id = $_POST['student_id'] ?? null;

if ($event_id && $student_id) {
    // Query to check if a rating exists
    $query = "SELECT ratings_id FROM ratings WHERE events_id = ? AND student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $event_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'rating' => $row['ratings_id'],
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No rating found.',
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input data.',
    ]);
}

$conn->close();
?>
