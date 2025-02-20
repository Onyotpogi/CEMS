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
