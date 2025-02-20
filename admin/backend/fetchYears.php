<?php
include('../include/config.php');

header('Content-Type: application/json');

$sql = "SELECT * FROM year_level ORDER BY level ASC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
