<?php
include('auth.php');
$stmtStudent = $conn->prepare("SELECT * FROM students WHERE user_id = ? LIMIT 1");
    $stmtStudent->bind_param("s", $userId);
    $stmtStudent->execute();
    $resultStudent = $stmtStudent->get_result();

    // Check if the result set is empty
    if ($resultStudent->num_rows === 0) {
        // Handle the case where no student is found
        $rowStudent = null; // Optionally set the row variable to null
        header("Location: ../student/profile.php");
    } else {
        $rowStudent = $resultStudent->fetch_assoc();
        // Proceed with processing $rowUStudent
    }
?>