<?php
// Include database connection (adjust the file path accordingly)
include('admin/include/config.php');

// Check if the form data was sent via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'Student';
    $status = 'Inactive';

    // Validate input
    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty!";
        exit();
    }

    // Hash the password for security
    $hashedPassword = md5($password);

    // Prepare SQL query to insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (name, username, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss",$name , $username, $hashedPassword, $role, $status);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error registering user: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
