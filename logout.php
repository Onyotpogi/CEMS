<?php
include('admin/include/config.php');
// Start the session
session_start();
date_default_timezone_set('Asia/Manila'); // Set timezone to Manila

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user['role'] === 'Admin'){

    }else{
        $log_stmt = $conn->prepare("INSERT INTO user_log (user_id, action, date_time) VALUES (?, ?, ?)"); 
        $action = "Time Out";
        $date_time = date("Y-m-d H:i:s"); // Current timestamp

        $log_stmt->bind_param('sss', $user['user_id'], $action, $date_time);
        $log_stmt->execute();
        $log_stmt->close();
    }

// Destroy all session data
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session itself

// Redirect to the login page or homepage
header("Location: index.php");
exit();
?>