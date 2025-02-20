<script>
    $(document).ready(function () {
    function fetchData(page = 1) {
        const search = $('input[placeholder="Search name.."]').val();
        const event_id = $('#eventSelect').val();
        const course_id = $('#courseSelect').val();
        $.ajax({
            url: 'backend/fetch_attendance.php',
            type: 'POST',
            data: {
                search: search,
                event_id: event_id,
                course_id: course_id,
                page: page,
            },
            dataType: 'json',
            success: function (response) {
                const dataBody = $('#data-body');
                const pagination = $('#pagination');
                dataBody.empty();
                pagination.empty();
                // Append data to the table
                if (response.data.length > 0) {
                    response.data.forEach(item => {
                        dataBody.append(`
                            <tr>
                                <td>${item.student_id}</td>
                                <td>${item.first_name}</td>
                                <td>${item.course}</td>
                                <td>${item.level}</td>
                                <td>${item.timein || 'N/A'}</td>
                                <td>${item.timeout || 'N/A'}</td>
                                <td>${item.date || 'N/A'}</td>
                                <td>${item.title}</td>
                            </tr>
                        `);
                    });
                } else {
                    dataBody.append(`
                        <tr>
                            <td colspan="7" class="text-center">No results found.</td>
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
    $('input[placeholder="Search name.."], #eventSelect, #courseSelect').on('change keyup', function () {
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