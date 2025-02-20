<?php
// Include database connection
include '../include/config.php';

function Events($conn, $studentId) {
    // Prepare the SQL query
    $sql = "SELECT * FROM `events` WHERE `date_from` < NOW()";
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $eventId = $row['event_id'];
            addAttendance($conn, $eventId, $studentId);
        }
    }
}

function addAttendance($conn, $eventId, $studentId) {
    $sql = "INSERT INTO `attendance` (`event_id`, `student_id`, `status`) VALUES (?, ?, 'unread')";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("is", $eventId, $studentId);
        $stmt->execute();
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error preparing attendance statement: ' . $conn->error]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    $fname = $_POST['fname'] ?? null;
    $mname = $_POST['mname'] ?? null;
    $lname = $_POST['lname'] ?? null;
    $year_level = $_POST['yearLevel'] ?? null;
    $course = $_POST['course'] ?? null;
    $userId = $_POST['userId'] ?? null;

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
        // **Start transaction**
        $conn->begin_transaction();

        try {
            // **Check if student exists**
            $checkQuery = "SELECT profile_pic FROM students WHERE user_id = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $userId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $existingStudent = $result->fetch_assoc();
            $checkStmt->close();

            if ($existingStudent) {
                // **Update existing student record**
                if ($profilePicPath) {
                    // Update with new profile picture
                    $query = "UPDATE students SET user_id = ?, profile_pic = ?, first_name = ?, middle_name = ?, last_name = ?, course = ?, year = ? WHERE student_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssssssss", $userId, $profilePicPath, $fname, $mname, $lname, $course, $year_level, $student_id);
                } else {
                    // Update without changing profile picture
                    $query = "UPDATE students SET user_id = ?, first_name = ?, middle_name = ?, last_name = ?, course = ?, year = ? WHERE student_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssssss", $userId, $fname, $mname, $lname, $course, $year_level, $student_id);
                }
            } else {
                // **Insert new student record**
                $query = "INSERT INTO students (`student_id`, `user_id`, `profile_pic`, `first_name`, `middle_name`, `last_name`, `course`, `year`)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssssss", $student_id, $userId, $profilePicPath, $fname, $mname, $lname, $course, $year_level);
            }

            // **Execute query**
            if (!$stmt->execute()) {
                throw new Exception("Database error: " . $stmt->error);
            }

            // **If inserting a new student, register attendance**
            if (!$existingStudent) {
                // Events($conn, $student_id);
            }

            // **Commit transaction**
            $conn->commit();

            echo json_encode(['success' => 'Profile saved successfully.']);
        } catch (Exception $e) {
            // **Rollback transaction on error**
            $conn->rollback();
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Missing required fields.']);
    }
}
?>
