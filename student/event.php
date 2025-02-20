

<!DOCTYPE html>
<html lang="en">
    <?php include('include/head.php') ?>
    <style>
        .event-section {
            padding-top: 10rem;
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
    <body>
        <?php include('include/navbar.php') ?>

        <!-- Masthead-->
        <!-- Events-->
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
                              if($event['attendance_status'] == 'timein'){
                                echo 
                                '
                                <div>
                                  <button class="btn btn-sm btn-success" data-value="timein" id="attendanceBtn">Time in</button>
                              </div>
                                ';
                              }else if($event['attendance_status'] == 'timeout'){
                                echo '
                                <button type="button" class="btn btn-primary" id="timeout">
                                    Time out
                                </button>
                                ';
                              }
                            ?>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ratings modal -->
<!-- Rating Modal -->
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

        
        <?php include('include/script.php'); ?>
        
<!-- JavaScript -->   
<script>
  
  const stars = document.querySelectorAll('.star'); // Select all stars
  let currentRating = 0; // Track the current rating

  // Function to highlight stars
  function highlightStars(rating) {
    stars.forEach((star, index) => {
      if (index < rating) {
        star.classList.add('selected'); // Highlight star
      } else {
        star.classList.remove('selected'); // Remove highlight
      }
    });
  }

  // Add event listeners to stars
  stars.forEach((star, index) => {
    // Highlight stars on hover
    star.addEventListener('mouseover', () => {
      highlightStars(index + 1);
    });

    // Reset highlight on mouseout
    star.addEventListener('mouseout', () => {
      highlightStars(currentRating);
    });

    // Update rating on click
    star.addEventListener('click', () => {
      currentRating = index + 1; // Update to clicked star value
      highlightStars(currentRating);
      console.log(`Clicked star: ${currentRating}`); // Debugging
    });
  });

  // Handle rating submission

  function attendances(attendance, eventId, studentId){
    $.ajax({
      url: 'backend/attendance.php', // Replace with your PHP endpoint
      type: 'POST',
      data: {
        event_id: eventId,
        student_id: studentId,
        attendance: attendance,
      },
      dataType: 'json',
      success: function (response) {
        if(attendance == "timein"){
          var a = "Timein";
        }else{
          var a = "Timeout";
        }

        toastr.success(a + ' successful!', 'Success');
      },
      error: function (xhr, status, error) {
        alert('Error submitting rating. Please try again.');
        console.error('Error:', error);
        console.log('Response:', xhr.responseText);
      }
    });
  }
$('#submitRating').on('click', function () {
  const feedback = $('#feedback').val().trim();
  
  if (currentRating > 0 && feedback) {
    const ratings = currentRating; // Ensure correct value
    const eventId = "<?= $_GET['id'];?>"; // Event ID from PHP
    const studentId = "<?= $rowStudent['student_id']?>"; // Replace with dynamic student ID if necessary
    // AJAX request
    console.log(studentId)
    $.ajax({
      url: 'backend/addRatings.php', // Replace with your PHP endpoint
      type: 'POST',
      data: {
        rating: ratings,
        feedback: feedback,
        event_id: eventId,
        student_id: studentId,
      },
      dataType: 'json',
      success: function (response) {
        alert('Rating submitted successfully!');
        if(response.success === true){
          console.log(true)
        }else{
          console.log(response)
        }
        $('#ratingModal').modal('hide');

        // Reset form
        currentRating = 0;
        highlightStars(currentRating);
        // $('#feedback').val(''); // Clear feedback
        // modal.hide('#ratingModal');
      },
      error: function (xhr, status, error) {
        alert('Error submitting rating. Please try again.');
        console.error('Error:', error);
        console.log('Response:', xhr.responseText);
      }
    });
  } else {
    alert('Please select a rating and provide feedback.');
  }
});
$(document).on('click', '#attendanceBtn', function(){
  var attendance = $(this).data('value');
  const eventId = "<?= $_GET['id'];?>"; // Event ID from PHP
  const studentId = "<?= $rowStudent['student_id']?>"; // Replace with dynamic student ID if necessary
  attendances(attendance, eventId, studentId);
})

$(document).on('click', '#timeout', function(){
  
  const eventId = "<?= $_GET['id'];?>"; // Event ID from PHP
  const studentId = "<?= $rowStudent['student_id']?>"; // Replace with dynamic student ID if necessary
  var attendance = "timeout";
  $.ajax({
    url: 'backend/checkRatings.php', // Replace with your PHP endpoint
    type: 'POST',
    data: {
      event_id: eventId,
      student_id: studentId,
    },
    dataType: 'json',
    success: function (response) {
      if(response.success){
        attendances(attendance, eventId, studentId);
      }else{
        $('#ratingModal').modal('show');
      }
    },
    error: function (xhr, status, error) {
      alert('Error submitting rating. Please try again.');
      console.error('Error:', error);
      console.log('Response:', xhr.responseText);
    }
  });
  
})
</script>



<script>
    // Define a fixed rating value (example: 3.5 out of 5)
const fixedRating = <?= $ratingStar;?>;

// Loop through all stars and mark those up to the fixed rating value as selected
document.querySelectorAll('.stars').forEach(star => {
    const starValue = parseFloat(star.getAttribute('data-values'));

    if (starValue <= Math.floor(fixedRating)) {
        // Fully selected stars
        star.classList.add('selected');
    } else if (starValue === Math.ceil(fixedRating) && fixedRating % 1 !== 0) {
        // Half-selected star
        star.classList.add('half');
    }
});
</script>


    </body>
</html>
