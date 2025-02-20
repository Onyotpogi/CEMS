<?php
// Include database connection
include '../include/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    $fname = isset($_POST['fname']) ? $_POST['fname'] : null;
    $mname = isset($_POST['mname']) ? $_POST['mname'] : null;
    $lname = isset($_POST['lname']) ? $_POST['lname'] : null;
    $year_level = isset($_POST['yearLevel']) ? $_POST['yearLevel'] : null;
    $course = isset($_POST['course']) ? $_POST['course'] : null;
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;

    $profilePicPath = null;

    // Handle file upload
    if (isset($_FILES['pPic']) && $_FILES['pPic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['pPic']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['pPic']['tmp_name'], $uploadFile)) {
            $profilePicPath = $uploadFile;
        } else {
            echo json_encode(['error' => 'Failed to upload profile picture.']);
            exit;
        }
    }

    if ($student_id && $fname && $lname) {
        // Insert/Update database query
        $query = "INSERT INTO students (`student_id`, `user_id`, `profile_pic`, `first_name`, `middle_name`, `last_name`, `course`, `year`)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $student_id, $userId, $profilePicPath, $fname, $mname, $lname, $course, $year_level);

        if ($stmt->execute()) {
            echo json_encode(['success' => 'Profile updated successfully.']);
        } else {
            echo json_encode(['error' => 'Database error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['error' => 'Missing required fields.']);
    }
}
?>
