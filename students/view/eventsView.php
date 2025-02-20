<?php
date_default_timezone_set('Asia/Manila');

    $studentId = $rowStudent['student_id'];
  $eventId = $_GET['id'] ?? 0; // Get event ID from query parameter or other source


function checkAttendance($conn, $studentId, $eventId) {
    $dateNow = date('Y-m-d'); // Get the current date

    $query = "SELECT `event_id`, `student_id`, `timein`, `timeout`, `date` 
              FROM `attendance` 
              WHERE `student_id` = ? 
              AND `event_id` = ? 
              AND `date` = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(['error' => $conn->error]));
    }

    $stmt->bind_param("iis", $studentId, $eventId, $dateNow);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // Returns the attendance record or null if not found
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

  function event($conn, $eventId) {
      $sql = "SELECT `event_id`, `image`, `title`, `description`, `date_from`, `date_to`, `type` , `attendance_status`
              FROM `events`  
              inner join 
              categories as cat
              on
              events.category_id = cat.category_id
              WHERE `event_id` = ?";
      
      // Prepare and execute the query using a prepared statement to prevent SQL injection
      if ($stmt = $conn->prepare($sql)) {
          $stmt->bind_param("i", $eventId); // Bind the event ID as an integer
          $stmt->execute(); // Execute the query

          // Fetch the result
          $result = $stmt->get_result();
          
          // Check if an event was found
          if ($result->num_rows > 0) {
              $event = $result->fetch_assoc(); // Fetch the event details as an associative array
              return $event; // Return the event data
          } else {
              return null; // No event found
          }

          $stmt->close(); // Close the statement
      } else {
          return null; // Query preparation failed
      }
  }

  $ratingStar = getOverallRating($eventId, $conn);

  function getEventImages($conn, $eventId) {
      
      $sql = "SELECT image_path FROM event_images WHERE event_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $eventId);
      $stmt->execute();
      $result = $stmt->get_result();

      $images = [];
      while ($row = $result->fetch_assoc()) {
          $images[] = $row['image_path'];
      }
      return $images;
  }
  $event = event($conn, $eventId); // Fetch event details

  if ($event === null) {
      echo "<p>Event not found.</p>"; // Handle the case where no event is found
      exit; // Stop further execution
  }
  $eventImages = getEventImages($conn, $eventId); // Fetch related images

  // Format event dates
  $date_from = new DateTime($event['date_from']);
  $date_to = new DateTime($event['date_to']);
  $formattedStartDate = $date_from->format('F j, Y');
  $formattedEndDate = $date_to->format('F j, Y');
  $checkAttendance = checkAttendance($conn, $studentId, $eventId);
  $title =$event['title'];

?>
  <style>
        .event-section {
            padding-bottom: 10px;
        }
        .event-section h2 {
            color: black;
        }
        .event-section p {
            margin-bottom: 5rem;
        }
        /* Star Rating Style */
        .star {
    font-size: 2rem;
    cursor: pointer;
    color: #ccc; /* Default star color */
    transition: color 0.2s ease-in-out;
  }

  .star.selected {
    color: gold; /* Highlighted star color */
  }

  .stars {
    color: gray; /* Default color for unselected stars */
    font-size: 24px;
    transition: color 0.3s ease;
}

.stars.selected {
    color: gold; /* Color for selected stars */
}

.stars.half {
    background: linear-gradient(to right, gold 50%, gray 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

    </style>
<section class="event-section text-center" id="events">  
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4"><?= strtoupper($event['type']);?> Event</h2>
                <!-- Event details placeholder -->
                <div id="eventDetails" class="mt-4 text-center p-4 bg-dark rounded shadow-sm">
                    
                    <!-- Image Slider -->
                    <div id="eventImageSlider" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php if (!empty($eventImages)): ?>
                                <?php foreach ($eventImages as $index => $imagePath): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                                        <img src="../admin/<?= $imagePath; ?>" class="d-block w-100 rounded" alt="Event Image <?= $index + 1; ?>" style="height: 300px; object-fit: cover;">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active">
                                    <img src="../admin/uploads/67807ed4be473-journal_skin_background_by_apexplus_d2w52yc-375w-2x.png" class="d-block w-100 rounded" alt="Default Event Image" style="height: 300px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#eventImageSlider" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventImageSlider" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <!-- Star rating -->
                    <div class="star-ratings mt-3">
                        <span class="stars" data-values="1">&#9733;</span>
                        <span class="stars" data-values="2">&#9733;</span>
                        <span class="stars" data-values="3">&#9733;</span>
                        <span class="stars" data-values="4">&#9733;</span>
                        <span class="stars" data-values="5">&#9733;</span>
                    </div>
                    <!-- Event Title -->
                    <h3 id="eventTitle" class="text-white fw-bold"><?= strtoupper($title);?></h3>

                    <!-- Event Description -->
                    <p id="eventDescription" class="text-muted mb-3"><?= ucwords(strtolower($event['description']));?></p>

                    <!-- Event Dates -->
                    <p id="eventDates" class="text-white small">
                        <span class="badge bg-primary me-1">Start: <?= $formattedStartDate;?></span>
                        <span class="badge bg-success">End: <?= $formattedEndDate;?></span>
                    </p>

                    <!-- Time in button -->
                    <!-- <div>
                        <button class="btn btn-sm btn-success">Time in</button>
                    </div> -->
                    <?php

                    if($checkAttendance['timeout'] === ''){
                        echo $checkAttendance['timeout'];
                    }else{
                        echo $checkAttendance['timein'];
                    }
                        if($event['attendance_status'] == 'timein'){
                            if (empty($checkAttendance['timein'])) {
                                echo '
                                <div class="text-center">
                                    <button class="btn btn-sm btn-success" data-value="timein" id="attendanceBtn">
                                        <i class="fas fa-clock"></i> Time In
                                    </button>
                                </div>
                                ';
                            } else {
                                echo '
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fas fa-check-circle"></i> Time In Successfully!
                                </div>
                                ';
                            }
                        
                        }else if($event['attendance_status'] == 'timeout'){
                            if (empty($checkAttendance['timeout'])) {
                                echo '
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="timeout">
                                        Time out
                                    </button>
                                </div>
                                ';
                            } else {
                                echo $checkAttendance['timeout'];
                                echo '
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fas fa-check-circle"></i> Time Out Successfully!
                                </div>
                                ';
                            }
                        }
                    ?>
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ratingModalLabel">Rate This Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <!-- Star Rating -->
        <div class="star-rating mb-3">
          <span class="star" data-value="1">&#9733;</span>
          <span class="star" data-value="2">&#9733;</span>
          <span class="star" data-value="3">&#9733;</span>
          <span class="star" data-value="4">&#9733;</span>
          <span class="star" data-value="5">&#9733;</span>
        </div>
        <!-- Feedback Input -->
        <textarea id="feedback" class="form-control" rows="3" placeholder="Leave your feedback here..."></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="submitRating" type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>