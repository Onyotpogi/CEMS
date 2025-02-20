<?php
include ('admin/include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Asia/Manila'); // Set timezone to Manila
    // Get data from the request
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $remember = isset($_POST['remember']) ? $_POST['remember'] : 0;

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed!']);
        exit;
    }

    // Query to check user credentials
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            // Set session or token
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;

            if ($remember) {
                setcookie('user_id', $user['user_id'], time() + (86400 * 30), "/"); // 30 days
            }

            // Insert into user_logs if user is a Student
            if ($user['role'] === 'Student') {
                $log_stmt = $conn->prepare("INSERT INTO user_log (user_id, action, date_time) VALUES (?, ?, ?)"); 
                $action = "Time In";
                $date_time = date("Y-m-d H:i:s"); // Current timestamp

                $log_stmt->bind_param('sss', $user['user_id'], $action, $date_time);
                $log_stmt->execute();
                $log_stmt->close();
            }

            echo json_encode(['success' => true, 'role' => $user['role'], 'message' => 'Login successful!', 'status' => $user['status']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect password!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found!']);
    }

    $stmt->close();
    $conn->close();
}
?>
