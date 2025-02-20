<script>

$(document).ready(function() {
    let currentPage = 1;
const rowsPerPage = 5;

// Fetch data with search and pagination
function fetchData(page, searchQuery = "") {
    let studentId = '<?php echo $rowStudent['student_id']; ?>';
    console.log(studentId);
    $.ajax({
        url: "backend/eventData.php",
        method: "GET",
        data: { studentId: studentId, page: page, rowsPerPage: rowsPerPage, search: encodeURIComponent(searchQuery) },
        dataType: "json",
        success: function(response) {
            // Populate table
            let rows = "";
            response.data.forEach(function(item) {
                rows += `
                    <tr>
                        <td><img src="../admin/${item.image_path}" alt="Image" width="50"></td>
                        <td>${item.title}</td>
                        <td>${item.type}</td>
                        <td>${item.description}</td>
                        <td>${item.date_from}</td>
                        <td>${item.date_to}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="window.location.href='index.php?link=eventView&id=${item.event_id}';">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $("#data-body").html(rows);

            // Populate pagination
            let paginationLinks = "";
            for (let i = 1; i <= response.totalPages; i++) {
                paginationLinks += `
                    <li class="page-item ${i === page ? "active" : ""}">
                        <a href="#" class="page-link" data-page="${i}">${i}</a>
                    </li>
                `;
            }
            $("#pagination").html(paginationLinks);
        },
        error: function() {
            alert("Failed to fetch data.");
        }
    });
}

// Initial data fetch
fetchData(currentPage);

// Handle pagination click
$(document).on("click", ".page-link", function(e) {
    e.preventDefault();
    currentPage = $(this).data("page");
    const searchQuery = $("#search-input").val();
    fetchData(currentPage, searchQuery);
});

// Handle search input
$("#search-input").on("keyup", function() {
    const searchQuery = $(this).val().trim();
    currentPage = 1; // Reset to first page when searching
    fetchData(currentPage, searchQuery);
});


});




//add 


// $(document).on('click', '#updateEvent', function(){
//     updateEvent();
// });

// function updateEvent() {
//     let formData = new FormData(); // Initialize FormData
//     const eventData = {
//         id: $('#eventid').val().trim(),
//         title: $('#update-event-title').val().trim(),
//         description: $('#update-event-description').val().trim(),
//         category_id: $('#update-event-category').val().trim(),
//         date_from: $('#update-event-date-from').val().trim(),
//         date_to: $('#update-event-date-to').val().trim()
//     };
//     console.log($('#update-event-title').val().trim())

//     if (!eventData.id || !eventData.title || !eventData.date_from || !eventData.date_to) {
//         alert('Please fill in all required fields.');
//         return;
//     }

//     $.ajax({
//         url: 'backend/updateEvent.php',
//         method: 'POST',
//         data: eventData,
//         dataType: 'json',
//     })
//     .done(function(response) {
//         if (response.success) {
//             alert('Event updated successfully!');
//             location.reload();
//         } else {
//             console.error('Server Response:', response);
//             alert('Failed to update event. ' + (response.message || 'Please try again.'));
//         }
//     })
//     .fail(function(jqXHR, textStatus, errorThrown) {
//         console.error('AJAX Error:', textStatus, errorThrown);
//         console.error('Server Response:', jqXHR.responseText);
//         alert("Error updating event. Check the console for details.");
//     });
// }


</script>
