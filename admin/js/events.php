<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('eventCalendar');

    if (!calendarEl) {
      console.error('Calendar element not found');
      return;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: [
        { title: 'Event 1', start: '2025-01-10' },
        { title: 'Event 2', start: '2025-01-15' },
      ],
    });

    calendar.render();
  });

$(document).ready(function() {
    let currentPage = 1;
    const rowsPerPage = 5;

    // Fetch data with search and pagination
    function fetchData(page, searchQuery = "") {
        $.ajax({
            url: "backend/eventData.php",
            method: "GET",
            data: { page: page, rowsPerPage: rowsPerPage, search: searchQuery },
            dataType: "json",
            success: function(response) {
        
                // Populate table
                let rows = "";
                response.data.forEach(function(item) {
                    rows += `
                        <tr>
                            <td><img src="${item.image_path}" alt="Image" width="50"></td>
                            <td>${item.title}</td>
                            <td>${item.type}</td>
                            <td>${item.description}</td>
                            <td>${item.date_from}</td>
                            <td>${item.date_to}</td>
                            <td>
                                <select id="actionSelect" class="action-select form" style="width: 100%; border-radius: 8px; padding: 10px;" data-id="${item.event_id}">
                                    <option value="">Select an Action</option>
                                    <option value="edit" data-bs-toggle="modal" data-bs-target="#updateEventModal">
                                         Edit
                                    </option>
                                    <option value="delete">
                                         Delete
                                    </option>
                                    <option value="view">
                                         View
                                    </option>
                                </select>

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
        const searchQuery = $(this).val();
        currentPage = 1; // Reset to first page
        fetchData(currentPage, searchQuery);
    });

    //add 
$(document).on("submit", "#add-event-form", function (e) {
    e.preventDefault();

    // Collect form data
    const formData = new FormData();
    formData.append("title", $("#event-title").val());
    formData.append("category", $("#event-category").val());
    formData.append("description", $("#event-description").val());
    formData.append("datetime_from", $("#event-date-from").val());
    formData.append("datetime_to", $("#event-date-to").val());

    // Handle multiple image uploads
    const files = $("#event-image")[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append("images[]", files[i]);
    }

    $.ajax({
    url: "backend/saveEvent.php",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
        console.log("Server Response:", response);

        if (response.status === "success") {
            Swal.fire({
                title: $("#event-title").val(),
                text: "Event added successfully!",
                icon: "success",
                timer: 3000,
                timerProgressBar: true
            });

            $("#addEventModal").modal("hide");
            $("#add-event-form")[0].reset();
            fetchData(1);
        } else {
            Swal.fire({
                title: "Error",
                text: response.message || "Something went wrong.",
                icon: "error"
            });
        }
    },
    error: function (xhr) {
        console.error("AJAX Error:", xhr.responseText);

        Swal.fire({
            title: "Error",
            text: `Server Response: ${xhr.responseText}`,
            icon: "error"
        });
    }
});
});

    $(document).on("submit", "#update-event-form", function (e) {
    e.preventDefault();

    // Collect form data
    const formData = new FormData();
    formData.append("id", $("#eventid").val());
    formData.append("title", $("#update-event-title").val());
    formData.append("category", $("#update-event-category").val());
    formData.append("description", $("#update-event-description").val());
    formData.append("datetime_from", $("#update-event-date-from").val());
    formData.append("datetime_to", $("#update-event-date-to").val());
    
    // Handle multiple image uploads
    const files = $("#update-event-image")[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append("images[]", files[i]); // Append each image
    }

    // Send the form data to the backend
    $.ajax({
        url: "backend/updateEvent.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json", // Expect JSON response
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: $("#update-event-title").val(),
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    title: "Update Failed",
                    text: response.message,
                    icon: "error",
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            $("#updateEventModal").modal("hide");
            $("#update-event-form")[0].reset();
            fetchData(1); // Refresh data table
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
            console.error("Server Response:", jqXHR.responseText);
            Swal.fire({
                title: "Error",
                text: "Failed to update event. Please check the console for details.",
                icon: "error",
                showCancelButton: false,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
});


});


function deleteEvent(eventId) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform AJAX request
            $.ajax({
                url: "backend/deleteEvent.php", // Path to your delete script
                type: "POST",
                data: { id: eventId },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Deleted!",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false, // Hide the button
                            timer: 2000, // Auto close after 2 seconds
                            timerProgressBar: true // Shows a progress bar
                        }).then(() => {
                            location.reload(); // Reload after the alert closes
                        });
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "Something went wrong.", "error");
                }
            });
        }
    });
}

$(document).on("change", ".action-select", function () {
    const action = $(this).val(); // Get selected action
    const eventId = $(this).data("id"); // Get event ID from data-id attribute

    if (action) {
        switch (action) {
            case "edit":
                showUpdateModal(eventId); // Pass the eventId to the modal function
                break;
            case "delete":
                deleteEvent(eventId);
                break;
            case "view":
                location.href = "index.php?link=events&id=" + eventId;
                break;
            default:
                alert("No valid action selected.");
        }

        // Reset the dropdown to its default state
        $(this).val("");
    }
});

function showUpdateModal(eventId) {
    // Initialize the modal using Bootstrap's Modal class
    const myModal = new bootstrap.Modal(document.getElementById('updateEventModal'));

    // Show the modal
    myModal.show();

    // Fetch event data using AJAX
    $.ajax({
        url: 'backend/fetchEventUpdate.php', // Replace with your server-side script
        method: 'GET',
        data: { id: eventId },
        success: function(response) {
            // Ensure the response is in the expected format (JSON)
            try {
                const data = JSON.parse(response); // If the response is not already a JSON object
                if (data && data.event_id) {
                    // Pre-fill the modal with the event data

                    $('#eventid').val(data.event_id);
                    $('#update-event-title').val(data.title);
                    $('#update-event-description').val(data.description);
                    $('#update-event-category').val(data.category_id);
                    $('#update-event-date-from').val(data.date_from);
                    $('#update-event-date-to').val(data.date_to);
                } else {
                    // Handle invalid data format
                    alert('Invalid event data received.');
                }
            } catch (error) {
                // Handle JSON parse errors
                alert('Error parsing event data.');
            }
        },
        error: function() {
            // Handle AJAX request error
            alert("Error fetching event data. Please try again.");
        }
    });
}

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
