<?php
include('include/config.php');
include('include/auth.php');
?>

<!DOCTYPE html>
<html lang="en">
    <?php include('include/head.php') ?>
    <style>
    </style>
    <body>
        <?php include('include/navbar.php') ?>

        <!-- Masthead-->
        <!-- Events-->
        <section class="text-center mb-3" style="margin-top: 100px;" id="events" >  
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-12">
                        <h2 class="mb-4"> Profile</h2>
                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                            Update Profile
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateProfileForm">
                    <div class="mb-4">
                        <label for="pPic" class="form-label fw-bold">Profile Picture</label>
                        <input type="file" class="form-control" id="pPic">
                    </div>

                    <div class="mb-4">
                        <label for="student_id" class="form-label fw-bold">Student ID</label>
                        <input type="text" class="form-control" id="student_id" placeholder="Enter your Student ID">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Name</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fname" placeholder="First Name">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="mname" placeholder="Middle Name">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="lname" placeholder="Last Name">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="yearLevel" class="form-label fw-bold">Year Level</label>
                            <select name="yearLevel" id="yearLevel" class="form-select">
                                <option value="">Select Year Level</option>
                                <?php
                                $query = "SELECT * FROM `year_level`";
                                $result = $conn->query($query);
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['year_id']) . '">' . htmlspecialchars($row['level']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No categories available</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="course" class="form-label fw-bold">Course</label>
                            <select name="course" id="course" class="form-select">
                                <option value="">Select Course</option>
                                <?php
                                $queryCourse = "SELECT * FROM `course`";
                                $resultCourse = $conn->query($queryCourse);
                                if ($resultCourse && $resultCourse->num_rows > 0) {
                                    while ($rowCourse = $resultCourse->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($rowCourse['course_id']) . '">' . htmlspecialchars($rowCourse['name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No categories available</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesButton">Save Changes</button>
            </div>
        </div>
    </div>
</div>

        <?php include('include/script.php'); ?>
        <script>
$(document).ready(function () {
    document.getElementById("saveChangesButton").addEventListener("click", function () {

        // Retrieve values from the modal
        const profilePicture = document.getElementById("pPic").files[0]; // For file input
        const studentId = document.getElementById("student_id").value;
        const firstName = document.getElementById("fname").value;
        const middleName = document.getElementById("mname").value;
        const lastName = document.getElementById("lname").value;
        const yearLevel = document.getElementById("yearLevel").value;
        const course = document.getElementById("course").value;
        const userId = "<?= $userId ?>"; // PHP variable for userId

        // Prepare the form data
        const formData = new FormData();
        formData.append("pPic", profilePicture);
        formData.append("student_id", studentId);
        formData.append("fname", firstName);
        formData.append("mname", middleName);
        formData.append("lname", lastName);
        formData.append("yearLevel", yearLevel);
        formData.append("course", course);
        formData.append("userId", userId);

        // Perform the AJAX request
        $.ajax({
            url: "backend/update_profile.php",
            type: "POST",
            data: formData,
            processData: false, // Required for FormData
            contentType: false, // Required for FormData
            success: function (response) {
                // Handle success
                alert("Profile updated successfully!");
                console.log(response); // Debugging response from the server
                $('#updateProfileModal').modal('hide'); // Close the modal
            },
            error: function (xhr, status, error) {
                // Handle error
                alert("An error occurred while updating the profile.");
                console.error(xhr.responseText);
            },
        });
    });
});


        </script>
    </body>
</html>
