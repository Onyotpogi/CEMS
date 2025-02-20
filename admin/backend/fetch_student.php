<?php
header('Content-Type: application/json');

include('../include/config.php');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$course = isset($_GET['course']) ? $_GET['course'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

$query = "SELECT student_id, profile_pic, first_name, middle_name, last_name, level, name 
          FROM students AS s 
          INNER JOIN year_level AS yl ON s.year = yl.year_id 
          INNER JOIN course AS c ON s.course = c.course_id 
          WHERE 1 ";

if (!empty($search)) {
    $query .= "AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR student_id LIKE '%$search%') ";
}
if (!empty($course)) {
    $query .= "AND c.course_id = '$course' ";
}
if (!empty($year)) {
    $query .= "AND yl.year_id = '$year' ";
}

$query .= "LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'student_id' => $row['student_id'],
        'image' => $row['profile_pic'],
        'name' => $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'],
        'course' => $row['name'],
        'year' => $row['level']
    ];
}

// Get total records for pagination
$total_query = "SELECT COUNT(*) as total FROM students";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

echo json_encode(['data' => $data, 'total_pages' => $total_pages, 'current_page' => $page]);
?>
