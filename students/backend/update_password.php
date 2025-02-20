<?php
session_start();
include('../include/config.php');

header("Content-Type: application/json"); // Ensure response is JSON format

$response = ["status" => "error", "message" => "Something went wrong."]; // Default response

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id']; // Ensure the user is authenticated
    $currentPassword = $_POST['currentPassword'];
    $hashCurrentPass = md5($currentPassword);
    $newPassword = md5($_POST['newPassword']);

    // Fetch current hashed password from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbPassword);
    $stmt->fetch();
    $stmt->close();

    // Check if the current password matches
    if ($dbPassword !== $hashCurrentPass) {
        $response = ["status" => "error", "message" => "Incorrect current password!"];
        echo json_encode($response);
        exit;
    }

    // Update the password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $newPassword, $userId);

    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "Password updated successfully!"];
    } else {
        $response = ["status" => "error", "message" => "Error updating password!"];
    }
    $stmt->close();
}

echo json_encode($response);
exit;
?>
