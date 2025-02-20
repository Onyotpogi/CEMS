<?php
include('../include/config.php');

function deleteImages($conn, $event_id) {
    // Get image paths before deleting from DB
    $stmt = $conn->prepare("SELECT image_path FROM event_images WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $filePath = "../" . $row['image_path']; // Convert relative path to absolute
        if (file_exists($filePath)) {
            unlink($filePath); // Delete physical file
        }
    }

    // Delete images from DB
    $stmt = $conn->prepare("DELETE FROM event_images WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->close();
}

function images($conn, $event_id) {
    if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
        return ["success" => true, "message" => "Event updated successfully."];
    }

    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $failedUploads = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        $imageName = uniqid() . '-' . basename($_FILES['images']['name'][$key]);
        $imagePath = $uploadDir . $imageName;
        $imagePathForDB = 'uploads/' . $imageName;

        if (move_uploaded_file($tmpName, $imagePath)) {
            
            deleteImages($conn, $event_id); // Delete old images first
            $stmtImage = $conn->prepare("INSERT INTO event_images (event_id, image_path) VALUES (?, ?)");
            $stmtImage->bind_param("is", $event_id, $imagePathForDB);
            $stmtImage->execute();
            $stmtImage->close();
        } else {
            $failedUploads[] = $_FILES['images']['name'][$key];
        }
    }

    if (!empty($failedUploads)) {
        return ["success" => false, "message" => "Failed to upload: " . implode(", ", $failedUploads)];
    }
    
    return ["success" => true];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['id'] ?? null;
    $category_id = $_POST['category'] ?? '';
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $date_from = $_POST['datetime_from'] ?? null;
    $date_to = $_POST['datetime_to'] ?? null;

    if (!$event_id || !$title || !$date_from || !$date_to) {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE events SET category_id = ?, title = ?, description = ?, date_from = ?, date_to = ? WHERE event_id = ?");
    $stmt->bind_param("sssssi", $category_id, $title, $description, $date_from, $date_to, $event_id);

    if ($stmt->execute()) {
        $uploadResult = images($conn, $event_id); // Upload new images
        if (!$uploadResult['success']) {
            echo json_encode($uploadResult);
            exit;
        }

        echo json_encode(["success" => true, "message" => "Event updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
