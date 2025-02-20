<?php
include('../include/config.php');

header('Content-Type: application/json');

$sql = "SELECT * FROM course ORDER BY name ASC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
