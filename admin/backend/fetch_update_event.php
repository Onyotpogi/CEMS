<?php
// Include your database connection
include('../include/config.php');

// Check if the eventId is provided in the GET request
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Prepare a query to fetch event data
    $query = "SELECT * FROM events WHERE event_id = ?";
    
    // Prepare the SQL statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the eventId parameter to the query
        $stmt->bind_param("i", $eventId);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Check if the event exists
        if ($result->num_rows > 0) {
            // Fetch the data as an associative array
            $eventData = $result->fetch_assoc();
            
            // Return the event data as JSON
            echo json_encode($eventData);
        } else {
            // If no event is found, return an error message
            echo json_encode(['error' => 'Event not found']);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // If the SQL query couldn't be prepared, return an error
        echo json_encode(['error' => 'Failed to prepare query']);
    }
} else {
    // If the eventId is not provided, return an error message
    echo json_encode(['error' => 'Event ID is required']);
}

// Close the database connection
$conn->close();
?>
