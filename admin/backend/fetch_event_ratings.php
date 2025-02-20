<?php
include('../include/config.php');

// Ensure JSON response
header('Content-Type: application/json');

$month = isset($_GET['month']) ? intval($_GET['month']) : null;
$year = isset($_GET['year']) ? intval($_GET['year']) : null;

// Check if month & year are provided
if (!empty($month) || !empty($year)) {
    $sql = "SELECT e.title, AVG(r.rating_star) AS average_rating 
            FROM ratings AS r 
            INNER JOIN events AS e ON r.events_id = e.event_id 
            WHERE MONTH(e.date_from) = ? AND YEAR(e.date_from) = ? 
            GROUP BY e.event_id 
            ORDER BY e.date_from DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $month, $year);
} else {
    // Fetch latest 5 events when month & year are empty
    $sql = "SELECT e.title, AVG(r.rating_star) AS average_rating 
            FROM ratings AS r 
            INNER JOIN events AS e ON r.events_id = e.event_id 
            GROUP BY e.event_id 
            ORDER BY e.date_from DESC 
            LIMIT 5";

    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
