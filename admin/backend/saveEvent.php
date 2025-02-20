<?php
header('Content-Type: application/json'); // Ensure proper JSON response
include('../include/config.php');

// function addAttendanceForm($conn, $event_id) {
//     $query = "SELECT `student_id` FROM `students`";
//     $result = $conn->query($query);

//     if (!$result) {
//         echo json_encode(['status' => 'error', 'message' => 'Failed to fetch students.']);
//         exit;
//     }

//     while ($row = $result->fetch_assoc()) {
//         $student_id = $row['student_id'];
//         $insertQuery = "INSERT INTO `attendance` (`event_id`, `student_id`, `status`) VALUES (?, ?, 'unread')";
//         $stmt = $conn->prepare($insertQuery);
//         if (!$stmt) {
//             echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $conn->error]);
//             exit;
//         }
//         $stmt->bind_param("is", $event_id, $student_id);
//         $stmt->execute();
//     }
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $datetime_from = $_POST['datetime_from'] ?? '';
    $datetime_to = $_POST['datetime_to'] ?? '';

    if (empty($title) || empty($description) || empty($datetime_from) || empty($datetime_to)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO events (category_id, title, description, date_from, date_to) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception('SQL Error: ' . $conn->error);
        }

        $stmt->bind_param("sssss", $category, $title, $description, $datetime_from, $datetime_to);
        $stmt->execute();
        $event_id = $stmt->insert_id;

        // addAttendanceForm($conn, $event_id);

        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            throw new Exception('No images uploaded.');
        }

        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $imageName = uniqid() . '-' . basename($_FILES['images']['name'][$key]);
            $imagePath = $uploadDir . $imageName;

            if (!move_uploaded_file($tmpName, $imagePath)) {
                throw new Exception('Failed to upload image: ' . $_FILES['images']['name'][$key]);
            }

            $imagePathForDB = 'uploads/' . $imageName;
            $stmtImage = $conn->prepare("INSERT INTO event_images (event_id, image_path) VALUES (?, ?)");
            if (!$stmtImage) {
                throw new Exception('SQL Error: ' . $conn->error);
            }
            $stmtImage->bind_param("is", $event_id, $imagePathForDB);
            $stmtImage->execute();
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Event saved successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
