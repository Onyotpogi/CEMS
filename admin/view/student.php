<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <input type="text" id="search-input" class="form-control w-auto" placeholder="Search..." style="min-width: 200px;">
                        <div class="d-flex gap-2">
                            <select id="course-filter" class="form-select">
                                <option value="">Select Course</option>
                                <?php
                                    $query = "SELECT `course_id`, `name` FROM `course`";
                                    $result = $conn->query($query);
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['course_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No courses available</option>';
                                    }
                                ?>
                            </select>
                            <select id="year-filter" class="form-select">
                                <option value="">Select Year</option>
                                <?php
                                $query = "SELECT `year_id`, `level` FROM `year_level`";
                                $result = $conn->query($query);
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['year_id'] . '">' . htmlspecialchars($row['level']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No years available</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="data-body">
                            <!-- Data will be appended here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul class="pagination justify-content-end" id="pagination">
                        <!-- Pagination links will be appended here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>