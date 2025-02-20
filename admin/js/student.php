<script>
    
$(document).ready(function () {
    function fetchData(page = 1, query = '', course = '', year = '') {
        $.ajax({
            url: 'backend/fetch_student.php', // Update with your backend script
            type: 'GET',
            data: { page: page, search: query, course: course, year: year },
            dataType: 'json',
            success: function (response) {
                let rows = '';

                if (response.data.length > 0) {
                    $.each(response.data, function (index, item) {
                        let formattedName = item.name.replace(/\b\w/g, char => char.toUpperCase());
                        rows += `<tr>
                            <td><img src="../students/backend/${item.image}" width="50"></td>
                            <td>${item.student_id}</td>
                            <td>${formattedName}</td>
                            <td>${item.course}</td>
                            <td>${item.year}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.student_id}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>`;
                    });
                } else {
                    rows = `<tr><td colspan="6" class="text-center">No records found</td></tr>`;
                }

                $('#data-body').html(rows);

                let paginationLinks = '';
                if (response.total_pages > 1) {
                    for (let i = 1; i <= response.total_pages; i++) {
                        paginationLinks += `<li class="page-item ${i === response.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
                    }
                }
                $('#pagination').html(paginationLinks);
            }
        });
    }

    $('#search-input, #course-filter, #year-filter').on('change keyup', function () {
        let query = $('#search-input').val();
        let course = $('#course-filter').val();
        let year = $('#year-filter').val();
        fetchData(1, query, course, year);
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        let page = $(this).data('page');
        let query = $('#search-input').val();
        let course = $('#course-filter').val();
        let year = $('#year-filter').val();
        fetchData(page, query, course, year);
    });

$(document).on('click', '.delete-btn', function () {
    let studentId = $(this).data('id');
    console.log(studentId)

    Swal.fire({
        title: "Are you sure?",
        text: "This record will be deleted permanently!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'backend/delete_student.php', // Your backend script
                type: 'POST',
                data: { id: studentId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Deleted!",
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });

                        fetchData(); // Refresh table
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong. Please try again later.",
                        icon: "error",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});


    fetchData(); // Load initial data
});


</script>