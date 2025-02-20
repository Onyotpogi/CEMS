<script>
    $(document).ready(function() {
    // Initial page load
    var currentPage = 1;
    var searchTerm = '';

    // Function to fetch user logs data with search and pagination
    function fetchUserLogs() {
        $.ajax({
            url: 'backend/fetch_user_logs.php',  // PHP script to fetch data
            type: 'GET',
            dataType: 'json',  // Expecting JSON response
            data: {
                search: searchTerm,
                page: currentPage
            },
            success: function(response) {
                // Clear existing table data
                $('#user-logs-data-body').empty();

                // Loop through the data and append it to the table
                $.each(response.logs, function(index, log) {
                    const logRow = `
                        <tr>
                            <td>${log.username}</td>
                            <td>${log.action}</td>
                            <td>${log.date_time}</td>
                        </tr>
                    `;
                    $('#user-logs-data-body').append(logRow);
                });

                // Update pagination
                updatePagination(response.total_pages, response.current_page);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Function to update pagination controls
    function updatePagination(totalPages, currentPage) {
        $('#pagination').empty();
        
        // Previous button
        if (currentPage > 1) {
            $('#pagination').append(`<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`);
        }

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            $('#pagination').append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Next button
        if (currentPage < totalPages) {
            $('#pagination').append(`<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`);
        }
    }

    // Fetch user logs when the page is ready
    fetchUserLogs();

    // Handle search input
    $('#search').on('keyup', function() {
        searchTerm = $(this).val();
        currentPage = 1;  // Reset to first page on search
        fetchUserLogs();
    });

    // Handle pagination click
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        currentPage = $(this).data('page');
        fetchUserLogs();
    });
});

</script>