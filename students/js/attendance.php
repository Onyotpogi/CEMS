<script>
    $(document).ready(function () {
    var studentId = '<?php echo $rowStudent['student_id']; ?>';    
    console.log(studentId)
    function fetchData(page = 1) {
        const event_id = $('#eventSelect').val();
        $.ajax({
            url: 'backend/fetch_attendance.php',
            type: 'POST',
            data: {
                studentId: studentId,
                event_id: event_id
            },
            dataType: 'json',
            success: function (response) {
                const dataBody = $('#data-body');
                const pagination = $('#pagination');
                dataBody.empty();
                pagination.empty();
                console.log(response)
                // Append data to the table
                if (response.data.length > 0) {
                    response.data.forEach(item => {
                        dataBody.append(`
                            <tr>
                                <td>${item.title}</td>
                                <td>${item.timein || 'N/A'}</td>
                                <td>${item.timeout || 'N/A'}</td>
                            </tr>
                        `);
                    });
                } else {
                    dataBody.append(`
                        <tr>
                            <td colspan="3" class="text-center">No results found.</td>
                        </tr>
                    `);
                }

                // Create pagination
                const totalPages = Math.ceil(response.totalRecords / response.limit);
                for (let i = 1; i <= totalPages; i++) {
                    pagination.append(`
                        <li class="page-item ${i === page ? 'active' : ''}">
                            <a class="page-link" href="#">${i}</a>
                        </li>
                    `);
                }
            },
            error: function (xhr, status, error) {
                alert('Error fetching data.');
                console.error(error);
            },
        });
    }

    // Fetch initial data
    fetchData();

    // Search or filter change
    $('#eventSelect').on('change keyup', function () {
        fetchData();
    });

    // Pagination click
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).text(), 10);
        fetchData(page);
    });
});

</script>