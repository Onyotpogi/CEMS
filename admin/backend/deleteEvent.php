<?php
include '../include/config.php'; // Include your database connection

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the delete query
    $sql = "DELETE FROM `events` WHERE event_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" represents an integer

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Event deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting event"]);
    }

    $stmt->close();
    $conn->close();
}
?>
