
<?php
// Fetch student details
$stmtStudent = $conn->prepare("SELECT student_id, user_id, profile_pic, first_name, middle_name, last_name, name, level FROM students as s
    INNER JOIN course as c ON s.course=c.course_id 
    INNER JOIN year_level as yl ON s.year = yl.year_id
    WHERE user_id = ?");
$stmtStudent->bind_param("s", $userId);
$stmtStudent->execute();
$resultStudent = $stmtStudent->get_result();
$rowStudent = $resultStudent->fetch_assoc();

// Set default values to empty strings if no data is found
$studentId = $rowStudent ? htmlspecialchars($rowStudent['student_id']) : "";
$profilePic = $rowStudent && !empty($rowStudent['profile_pic']) ? htmlspecialchars($rowStudent['profile_pic']) : "";
$firstName = $rowStudent ? htmlspecialchars($rowStudent['first_name']) : "";
$middleName = $rowStudent ? htmlspecialchars($rowStudent['middle_name']) : "";
$lastName = $rowStudent ? htmlspecialchars($rowStudent['last_name']) : "";
$yearLevel = $rowStudent ? htmlspecialchars($rowStudent['level']) : "";
$courseName = $rowStudent ? htmlspecialchars($rowStudent['name']) : "";
?>

<section class="text-center mb-5" id="profile">
    <div class="container px-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card p-4 shadow-lg border-0 rounded-4">
                    <div class="d-flex flex-column align-items-center">
                        <?php   
                            if ($rowStudent) {
                        ?>
                        <!-- Profile Image with Hover Effect -->
                        <div class="position-relative">
                            <img src="backend/<?php echo htmlspecialchars($rowStudent['profile_pic']); ?>" 
                                 id="profileImage" 
                                 class="rounded-circle border border-3 border-primary shadow-lg profile-img" 
                                 width="160" height="160" 
                                 alt="Profile Image">
                        </div>
                        <!-- Profile Details -->
                        <h4 id="profileName" class="mt-3 text-dark fw-bold">
                            <?php echo htmlspecialchars($rowStudent['first_name'] . ' ' . $rowStudent['middle_name'] . ' ' . $rowStudent['last_name']); ?>
                        </h4>
                        <p id="profileCourse" class="text-muted mb-1">
                            <i class="fas fa-graduation-cap me-2"></i> <?php echo htmlspecialchars($rowStudent['name']); ?>
                        </p>
                        <p id="profileYear" class="text-muted mb-3">
                            <i class="fas fa-calendar-alt me-2"></i> Year: <?php echo htmlspecialchars($rowStudent['level']); ?>
                        </p>
                        <?php
                            } else {
                                echo "<p class='text-center text-danger mt-3'>Update your profile!.</p>";
                            }
                        ?>
                        <!-- Buttons Section -->
                        <div class="d-flex flex-column flex-md-row gap-3 mt-2">
                            <button class="btn btn-primary px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                                <i class="fas fa-edit me-2"></i> Update Profile
                            </button>
                            <button class="btn btn-secondary px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">
                                <i class="fas fa-lock me-2"></i> Update Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateProfileForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="pPic" class="form-label fw-bold">Profile Picture</label>
                        <input type="file" class="form-control" id="pPic" required>
                        <?php if (!empty($profilePic)): ?>
                            <img src="backend/<?php echo $profilePic; ?>" alt="Profile Picture" class="mt-2 img-thumbnail" width="100">
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="student_id" class="form-label fw-bold">Student ID</label>
                        <input type="text" class="form-control" id="student_id" value="<?php echo $studentId; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Name</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fname" value="<?php echo $firstName; ?>" placeholder="First Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="mname" value="<?php echo $middleName; ?>" placeholder="Middle Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="lname" value="<?php echo $lastName; ?>" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="yearLevel" class="form-label fw-bold">Year Level</label>
                            <select name="yearLevel" id="yearLevel" class="form-select" required>
                                <option value="">Select Year Level</option>
                                <?php
                                $query = "SELECT * FROM `year_level`";
                                $result = $conn->query($query);
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($yearLevel == $row['level']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['year_id']) . '" ' . $selected . '>' . htmlspecialchars($row['level']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No year levels available</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="course" class="form-label fw-bold">Course</label>
                            <select name="course" id="course" class="form-select" required>
                                <option value="">Select Course</option>
                                <?php
                                $queryCourse = "SELECT * FROM `course`";
                                $resultCourse = $conn->query($queryCourse);
                                if ($resultCourse && $resultCourse->num_rows > 0) {
                                    while ($rowCourse = $resultCourse->fetch_assoc()) {
                                        $selected = ($courseName == $rowCourse['name']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($rowCourse['course_id']) . '" ' . $selected . '>' . htmlspecialchars($rowCourse['name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No courses available</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveChangesButton">Save Changes</button>
            </div>
        </div>
    </div>
</div>


<!-- Update Password Modal -->
<div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updatePasswordModalLabel">Update Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label fw-bold">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label fw-bold">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePasswordButton">Save Changes</button>
            </div>
        </div>
    </div>
</div>

