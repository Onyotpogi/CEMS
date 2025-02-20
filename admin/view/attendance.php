<style>
    .pagination {
        margin-top: 20px;
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        color: #007bff;
        border: 1px solid #007bff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: #007bff;
        color: white;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination .page-item.disabled .page-link {
        color: #ddd;
        pointer-events: none;
        background-color: #f8f9fa;
    }
    .form-select {
    width: 100%;
    padding: 5px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

</style>


<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <div class=" align-items-center">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form form-control" placeholder="Search name..">
                            </div>
                            <div class="col-md-3">
                                <select name="event" class="form-control" id="eventSelect">
                                    <option value="">Select Event</option>
                                    <?php

                                    // Fetch events
                                    $query = "SELECT `event_id`, `title` FROM `events`";
                                    $result = $conn->query($query);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['event_id'] . '">' . htmlspecialchars($row['title']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No events available</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="course" class="form-control" id="courseSelect">
                                    <option value="">Select Course</option>
                                    <?php
                                    // Fetch courses
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
                            </div>

                            <div class="col-md-3">
                                <select name="" class="form-control" id="">
                                <option value="">Select Year Level</option>
                                    
                                    <?php
                                    // Fetch courses
                                    $query = "SELECT `year_id`, `level` FROM `year_level`";
                                    $result = $conn->query($query);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . $row['year_id'] . '">' . htmlspecialchars($row['level']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No courses available</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Date</th>
                                <th>Events</th>
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

