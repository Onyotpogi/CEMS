<?php
include '../include/config.php'; // Ensure correct database connection

// Get the search term and page number from the AJAX request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;  // Number of items per page
$offset = ($page - 1) * $limit;  // Offset for pagination

// Search query with pagination
$sql = "SELECT username, action, date_time 
        FROM user_log as ul INNER join users as u on ul.user_id = u.user_id
        WHERE username LIKE ? OR action LIKE ? 
        ORDER BY date_time DESC 
        LIMIT ? OFFSET ?";

// Prepare the statement
$stmt = $conn->prepare($sql);
$searchTermWildcard = "%" . $searchTerm . "%";
$stmt->bind_param("ssii", $searchTermWildcard, $searchTermWildcard, $limit, $offset);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch all rows as associative arrays
$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

// Get the total number of logs for pagination
$totalSql = "SELECT COUNT(*) AS total FROM user_log as ul INNER join users as u on ul.user_id = u.user_id WHERE username LIKE ? OR action LIKE ?";
$totalStmt = $conn->prepare($totalSql);
$totalStmt->bind_param("ss", $searchTermWildcard, $searchTermWildcard);
$totalStmt->execute();
$totalResult = $totalStmt->get_result()->fetch_assoc();
$totalLogs = $totalResult['total'];
$totalPages = ceil($totalLogs / $limit);

// Return the data as JSON
echo json_encode([
    'logs' => $logs,
    'total_pages' => $totalPages,
    'current_page' => $page
]);

// Close the database connection
$stmt->close();
$totalStmt->close();
$conn->close();
?>
