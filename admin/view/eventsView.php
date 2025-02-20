<?php
date_default_timezone_set('Asia/Manila'); // Set timezone to Manila
// Check if the record already exists
$datenow = date('Y-m-d H:i:s'); 
$sampleDate = '2025-02-23 23:15:00';
        $eventId = $_GET['id'];
    function events($conn, $eventId){
        $stmtEvents = $conn->prepare("SELECT * FROM events WHERE event_id = ? LIMIT 1");
        $stmtEvents->bind_param("s", $eventId);
        $stmtEvents->execute();
        $resultEvents = $stmtEvents->get_result();
        $rowEvents = $resultEvents -> fetch_assoc();
        return $rowEvents;
    }

    function getEventImages($eventId, $conn)
{
    $query = "SELECT `id`, `event_id`, `image_path` FROM `event_images` WHERE `event_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['image_path'];
    }
    $stmt->close();
    return $images;
}

function getEventRatings($eventId, $conn)
{
    // Define the query
    $query = "SELECT `first_name`, `last_name`, `feedback`, `date` 
              FROM `ratings` AS rate 
              INNER JOIN students AS stud 
              ON rate.student_id = stud.student_id 
              WHERE rate.events_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("i", $eventId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch all rows as an associative array
        $ratings = $result->fetch_all(MYSQLI_ASSOC);

        // Close the statement
        $stmt->close();

        // Return the fetched ratings
        return $ratings;
    } else {
        // Handle the error if the statement could not be prepared
        die("Failed to prepare statement: " . $conn->error);
    }
}

function getOverallRating($eventId, $conn)
{
    // Query to fetch the rating stars for a specific event
    $query = "SELECT `rating_star` FROM `ratings` WHERE `events_id` = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind the event ID parameter
        $stmt->bind_param("i", $eventId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch all rating stars into an array
        $ratings = [];
        while ($row = $result->fetch_assoc()) {
            $ratings[] = (int)$row['rating_star'];
        }

        // Close the statement
        $stmt->close();

        // Calculate the overall rating
        if (count($ratings) > 0) {
            $totalRatings = array_sum($ratings); // Sum all ratings
            $overallRating = $totalRatings / count($ratings); // Calculate the average rating
            return round($overallRating, 1); // Return the average rounded to 1 decimal place
        } else {
            return 0; // Return 0 if no ratings are available
        }
    } else {
        // Handle errors in statement preparation
        die("Failed to prepare statement: " . $conn->error);
    }
}


    $ratingStar = getOverallRating($eventId, $conn);
    $roundedRating = floor($ratingStar);
    $event = events($conn, $eventId);
    $images = getEventImages($eventId, $conn);
    $ratings = getEventRatings($eventId, $conn);
?>

<div class="row justify-content-center mt-5">
    <!-- First Card -->
    <div class="col-md-6 mb-4">
    <div class="card shadow-lg rounded-lg border-light mx-auto">
    <div class="card-body text-center">
        <!-- Start of Image Slider -->
        <div id="imageCarousel<?= $eventId ?>" class="carousel slide mb-3" data-bs-ride="carousel">
            <div class="carousel-inner rounded shadow" style="width: 220px; height: 180px; margin: 0 auto;">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($image) ?>" 
                                alt="Event Image <?= $index + 1 ?>" 
                                class="d-block rounded mx-auto" 
                                style="object-fit: cover; width: 220px; height: 180px; border: 2px solid #ddd; transition: transform 0.3s ease;">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="placeholder.jpg" 
                            alt="No Image" 
                            class="d-block rounded mx-auto" 
                            style="object-fit: cover; width: 220px; height: 180px; border: 2px solid #ddd; transition: transform 0.3s ease;">
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($images) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel<?= $eventId ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel<?= $eventId ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
        <!-- End of Image Slider -->

        <!-- Event Details -->
        <h2 class="mb-3 text-primary font-weight-bold"><?= htmlspecialchars($event['title']) ?></h2>
        <p class="text-muted mb-2"><strong>Description:</strong> <?= htmlspecialchars($event['description']) ?></p>
        <p class="mb-2"><strong>Date & Time From:</strong> <?= htmlspecialchars($event['date_from']) ?></p>
        <p class="mb-3"><strong>Date & Time To:</strong> <?= htmlspecialchars($event['date_to']) ?></p>
        <?php
        // if($event['date_from'] < $datenow){
        
        $eventNow = date('Y-m-d H:i:s');
        if ($eventNow >= $event['date_from'] && $eventNow <= $event['date_to']) {

        ?>
        <select class="btn btn-primary btn-sm px-4 py-2" onchange="handleSelectChange(this)">
        <?php

                    // Your code here
                    if($event['attendance_status'] == 'timein'){
                        echo '<option value="timein">Time In</option>
                            <option value="timeout">Time Out</option>
                            <option value="na">Not Started/finish</option>'
                            ;
                    }else if($event['attendance_status'] == 'timeout'){
                        echo '
                            <option value="timeout"> Time Out </option>
                            <option value="timein">Time In</option>
                            <option value="na">Not Started/finish</option>';
                    }else{
                        echo '
                            <option value="na">Not Started/finish</option>
                            <option value="timein">Time In</option>
                            <option value="timeout">Time Out</option>';
                    }
            ?>
        </select>
        <?php
        }else{
            if ($eventNow < $event['date_from']) {
                echo '<p class="glow-text">🚀 Get Ready! Something BIG is Coming! 🔥</p>';
            } else {
                echo '<p class="glow-text">🎉 The Event Has Ended – Stay Tuned for More! 🎊</p>';
            }
        } 
    // } ?>
    </div>
</div>
    </div>



    <!-- Second Card -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-lg rounded-lg border-light">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <p class="mb-2">Rating: </p><bold style="font-size: 20px;"><?= $ratingStar?></bold>
                <div class="rating mb-3">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= $i <= $roundedRating ? 'highlight' : '' ?>" 
                            data-value="4.5">&#9733;</span>
                    <?php endfor; ?>
                </div>

                <p class="mb-2">Feedback:</p>
                <div class="comments-container">
                    <div class="comment-item">
                        <?php
                            foreach ($ratings as $rating) {
                                echo "<p><strong>" . htmlspecialchars($rating['first_name'] . " " . $rating['last_name']) . ":</strong> " . htmlspecialchars($rating['feedback']) . "</p>";
                                echo "<p class='text-muted'>" . htmlspecialchars($rating['date']) . "</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add styles for hover effect and scrollable card -->
<style>
    .glow-text {
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
            text-shadow: 0 0 10px #ff00ff, 0 0 20px #ff00ff, 0 0 30px #ff00ff, 0 0 40px #ff00ff;
            animation: flicker 1.5s infinite alternate;
        }
        @keyframes flicker {
            0% { opacity: 1; text-shadow: 0 0 10px #ff00ff, 0 0 20px #ff00ff; }
            50% { opacity: 0.8; text-shadow: 0 0 15px #ff00ff, 0 0 30px #ff00ff; }
            100% { opacity: 1; text-shadow: 0 0 10px #ff00ff, 0 0 20px #ff00ff; }
        }
  .star {
        font-size: 24px;
        color: #ccc; /* Default color for unselected stars */
        cursor: pointer;
    }
    .star.highlight {
        color: gold; /* Color for selected stars */
    }

    .card:hover {
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .card img {
        transition: transform 0.3s ease;
    }

    .card img:hover {
        transform: scale(1.1);
    }

    /* Scrollable comment section */
    .comments-container {
        max-height: 200px; /* Set the height of the scrollable area */
        overflow-y: auto;
        padding: 10px;
        border-top: 1px solid #ddd;
        margin-top: 10px;
    }

    .comment-item {
        margin-bottom: 15px;
    }

    .comment-item p {
        margin: 0;
    }

    .btn-primary {
        margin-top: 15px;
    }
</style>

<script>
   function handleSelectChange(select) {
    const value = $(select).val();
    var eventId = '<?= $eventId?>';
    if (value) {
        $.ajax({
    url: "backend/update-attendance-status.php", // Replace with your server endpoint
    type: "POST",
    data: { 
        action: value, 
        eventId: eventId 
    },
    dataType: "json", // Ensure jQuery treats response as JSON
    success: function (response) {
        console.log(response)
        // Assuming the response is JSON, parse it if necessary
        try {
            var data = JSON.parse(response);
            
            if (data.status === 'success') {
                // Update the content dynamically
                $("#content").html(data.message); // Or use data.data if you have actual content
            } else {
                $("#content").html("<p>" + data.message + "</p>");
            }
        } catch (e) {
            console.error("Error parsing JSON response:", e);
            $("#content").html("<p>Something went wrong. Please try again later.</p>");
        }
    },
    error: function (xhr, status, error) {
        // Handle AJAX errors
        console.error("AJAX Error: " + error);
        $("#content").html("<p>Something went wrong. Please try again later.</p>");
    },
    });

        }
    }

</script>