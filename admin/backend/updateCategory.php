<?php
include('../include/config.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and trim inputs
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';

    // Validate input
    if (empty($id) || empty($category_name)) {
        echo json_encode(["success" => false, "error" => "Invalid input."]);
        exit;
    }

    // Check if the category exists
    $stmt = $conn->prepare("SELECT type FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["success" => false, "error" => "Category not found."]);
        $stmt->close();
        exit;
    }

    // Bind the existing name for comparison
    $stmt->bind_result($existingName);
    $stmt->fetch();
    $stmt->close();

    // Check if there is a change in the category name
    if ($category_name === $existingName) {
        echo json_encode(["success" => false, "error" => "No changes detected."]);
        exit;
    }

    // Update the category name
    $stmt = $conn->prepare("UPDATE categories SET type = ? WHERE category_id = ?");
    $stmt->bind_param("si", $category_name, $id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Category updated successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => "Error updating category: " . $conn->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
