<?php
include('../include/config.php');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    if(empty($category_name)) {
        echo json_encode(["success" => false, "error" => "Category name is required."]);
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO categories (type) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    if($stmt->execute()){
        echo json_encode(["success" => true, "message" => "Category added successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
