<style>
    /* Custom style for the dropdown */
.select-wrapper {
    position: relative;
    width: 100%;
    max-width: 200px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
}

/* Optional: Custom arrow */
.form-select {
    appearance: none;
    padding-right: 10px;
}

.form-select:focus {
    outline: none;
    box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.25);
}

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
                <div class="d-flex justify-content-between align-items-center">
  <button id="add-event-btn" class="btn btn-success d-flex align-items-center px-3 py-2 rounded-pill shadow-sm"
    data-bs-toggle="modal" data-bs-target="#addEventModal">
    <i class="fa fa-plus me-2"></i> Add Event
  </button>
  <div class="position-relative">
    <i class="fa fa-search position-absolute text-muted" style="top: 50%; left: 12px; transform: translateY(-50%);"></i>
    <input type="text" id="search-input" class="form-control ps-4" placeholder="Search..." style="border-radius: 20px; width: 250px; height: 40px;">
  </div>
</div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Date from</th>
                                <th>Date to</th>
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

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-event-form">
                    <div class="mb-3">
                        <label for="event-title" class="form-label">Title</label>
                        <input type="text" id="event-title" class="form-control" placeholder="Enter event title" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-description" class="form-label">Category</label>
                        <select name="" id="event-category" class="form-control">
                            <option value="">Select Categories</option>
                            <?php
                            $query = "SELECT * FROM `categories`";
                            $result = $conn->query($query);
                            
                            // Check if query execution was successful
                            if ($result && $result->num_rows > 0) {
                                // Loop through the results and generate option elements
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['category_id']) . '">' . htmlspecialchars($row['type']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No categories available</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event-description" class="form-label">Description</label>
                        <textarea id="event-description" class="form-control" rows="3" placeholder="Enter event description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="event-date-from" class="form-label">Date From</label>
                        <input type="datetime-local" id="event-date-from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-date-to" class="form-label">Date To</label>
                        <input type="datetime-local" id="event-date-to" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-image" class="form-label">Image</label>
                        <input type="file" name="images[]" id="event-image" class="form-control" multiple required>
                        
                    </div>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Event Modal -->
<div class="modal fade" id="updateEventModal" tabindex="-1" aria-labelledby="updateEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateEventModalLabel">Update Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-event-form">
                    <input type="hidden" id="eventid" name="event_id">
                    <div class="mb-3">
                        <label for="event-title" class="form-label">Title</label>
                        <input type="text" id="update-event-title" class="form-control" placeholder="Enter event title" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-category" class="form-label">Category</label>
                        <select name="update-event-category" id="update-event-category" class="form-control">
                            <option value="">Select Categories</option>
                            <?php
                            $query = "SELECT * FROM `categories`";
                            $result = $conn->query($query);
                            
                            // Check if query execution was successful
                            if ($result && $result->num_rows > 0) {
                                // Loop through the results and generate option elements
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['category_id']) . '">' . htmlspecialchars($row['type']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No categories available</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event-description" class="form-label">Description</label>
                        <textarea id="update-event-description" class="form-control" rows="3" placeholder="Enter event description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="event-date-from" class="form-label">Date From</label>
                        <input type="datetime-local" id="update-event-date-from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-date-to" class="form-label">Date To</label>
                        <input type="datetime-local" id="update-event-date-to" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="event-image" class="form-label">Image</label>
                        <input type="file" name="update-images[]" id="update-event-image" class="form-control" multiple>
                    </div>
                    <button type="submit" id="updateEvent" class="btn btn-primary">Update Event</button>
                </form>
            </div>
        </div>
    </div>
</div>
