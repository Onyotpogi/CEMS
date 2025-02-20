<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                <div class="mb-3 d-flex justify-content-between">
                    <h4>Event Rating Reports</h4>
                    <button class="btn btn-primary" id="printBtn">
                        🖨 Print Report
                    </button>
                </div>
                    <table class="table table-striped" id="attendanceReportsTable">
                        <thead>
                            <tr>
                                <th>Events</th>
                                <th>Student Timein</th>
                                <th>Student timeout</th>
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