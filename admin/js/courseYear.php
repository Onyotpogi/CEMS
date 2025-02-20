
<script>
$(document).ready(function () {
    // Fetch Course and Year data on page load
    fetchCourseData();
    fetchYearData();

    // Function to fetch Course data
    function fetchCourseData() {
        $.ajax({
            url: "backend/fetchCourses.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#course-data-body").empty();
                if (response.length > 0) {
                    $.each(response, function (index, item) {
                        $("#course-data-body").append(`
                            <tr>
                                <td>${item.name}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm btn-edit-course" data-id="${item.course_id}" data-name="${item.name}">Edit</button>
                                    <button class="btn btn-danger btn-sm btn-delete-course" data-id="${item.course_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $("#course-data-body").append(`<tr><td colspan="2" class="text-center">No Courses Available</td></tr>`);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching courses:", error);
            }
        });
    }

    // Function to fetch Year data
    function fetchYearData() {
        $.ajax({
            url: "backend/fetchYears.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#year-data-body").empty();
                if (response.length > 0) {
                    $.each(response, function (index, item) {
                        $("#year-data-body").append(`
                            <tr>
                                <td>${item.level}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm btn-edit-year" data-id="${item.year_id}" data-level="${item.level}">Edit</button>
                                    <button class="btn btn-danger btn-sm btn-delete-year" data-id="${item.year_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $("#year-data-body").append(`<tr><td colspan="2" class="text-center">No Years Available</td></tr>`);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching years:", error);
            }
        });
    }

    // Handle Course Form Submission (Add)
    $("#courseForm").submit(function (e) {
        e.preventDefault();
        let courseName = $("#courseName").val().trim();

        if (courseName === "") {
            alert("Please enter a course name.");
            return;
        }

        $.ajax({
            url: "backend/addCourse.php",
            type: "POST",
            data: { course_name: courseName },
            success: function (response) {
                $("#addCourseModal").modal("hide");
                $("#courseForm")[0].reset();
                fetchCourseData();
            },
            error: function (xhr, status, error) {
                console.error("Error adding course:", error);
            }
        });
    });

    // Handle Year Form Submission (Add)
    $("#yearForm").submit(function (e) {
        e.preventDefault();
        let yearName = $("#yearName").val().trim();

        if (yearName === "") {
            alert("Please enter a year.");
            return;
        }

        $.ajax({
            url: "backend/addYear.php",
            type: "POST",
            data: { year_name: yearName },
            success: function (response) {
                $("#addYearModal").modal("hide");
                $("#yearForm")[0].reset();
                fetchYearData();
            },
            error: function (xhr, status, error) {
                console.error("Error adding year:", error);
            }
        });
    });

 
    // Handle Course Delete with SweetAlert2
    $(document).on("click", ".btn-delete-course", function () {
        let id = $(this).data("id");
        Swal.fire({
            title: 'Are you sure you want to delete this course?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "backend/deleteCourse.php",
                    type: "POST",
                    data: { id: id },
                    success: function (response) {
                        
                        toastr.success("The course has been deleted.");
                        
                        fetchCourseData(); // Refresh course data
                    },
                    error: function (xhr, status, error) {
                        console.error("Error deleting course:", error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the course.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

  

    // Handle Year Delete
    // Handle Year Delete with SweetAlert2
    $(document).on("click", ".btn-delete-year", function () {
        let id = $(this).data("id");
        Swal.fire({
            title: 'Are you sure you want to delete this year?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "backend/deleteYear.php",
                    type: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            toastr.success("The year has been deleted.");
                            fetchYearData(); // Refresh year data after deletion
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error,
                                icon: 'error'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error deleting year:", xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An unexpected error occurred. Please try again.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // update course 
    // Handle Course Edit Button Click
    $(document).on("click", ".btn-edit-course", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        $("#editCourseId").val(id);
        $("#editCourseName").val(name).data("original", name); // Store original value for comparison
        $("#editCourseModal").modal("show");
    });

    // Handle Course Update Form Submission
    $("#editCourseForm").submit(function (e) {
        e.preventDefault();
        let id = $("#editCourseId").val().trim();
        let name = $("#editCourseName").val().trim();

        // Check if fields are empty
        if (id === "" || name === "") {
            toastr.warning("Please enter a valid course name.");
            return;
        }

        // Prevent updating if the name is the same as before
        let oldName = $("#editCourseName").data("original");
        if (name === oldName) {
            toastr.info("No changes detected.");
            return;
        }

        $.ajax({
            url: "backend/updateCourse.php",
            type: "POST",
            data: { id: id, course_name: name },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    toastr.success("Course updated successfully.");
                    $("#editCourseModal").modal("hide");
                    fetchCourseData(); // Refresh course data
                } else {
                    toastr.error("Error updating course: " + response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error updating course:", xhr.responseText);
                toastr.error("An unexpected error occurred. Please try again.");
            }
        });
    });


        // Handle Year Edit Button Click
    $(document).on("click", ".btn-edit-year", function () {
        let id = $(this).data("id");
        let level = $(this).data("level");
        $("#editYearId").val(id);
        $("#editYearName").val(level).data("original", level); // Store the original value
        $("#editYearModal").modal("show");
    });

    // Handle Year Update Form Submission
    $("#editYearForm").submit(function (e) {
        e.preventDefault();
        let id = $("#editYearId").val().trim();
        let level = $("#editYearName").val().trim();

        // Check if fields are empty
        if (id === "" || level === "") {
            
            toastr.warning("Please enter a valid year level.");
            return;
        }

        // Prevent updating if the level is the same as before
        let oldLevel = $("#editYearName").data("original");
        if (level === oldLevel) {
            alert("No changes detected.");
            return;
        }

        $.ajax({
            url: "backend/updateYear.php",
            type: "POST",
            data: { id: id, year_level: level },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    
                    toastr.success("Year updated successfully.");
                    $("#editYearModal").modal("hide");
                    fetchYearData(); // Refresh year data
                } else {
                    alert("Error updating year: " + response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error updating year:", xhr.responseText);
                alert("An unexpected error occurred. Please try again.");
            }
        });
    });



});
</script>
