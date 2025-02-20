<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    
                    <div class="mb-3 d-flex justify-content-between">
                        <h4>Event Ratings Report</h4>
                        <button class="btn btn-primary" id="printBtn">
                            🖨 Print Report
                        </button>
                    </div>

                    <table class="table table-striped" id="reportTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Ratings</th>
                                <th>Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $limit = 10; // Set default limit
                            $offset = 0; // Default offset for pagination

                            $sql = "SELECT e.title, AVG(r.rating_star) AS rating, COUNT(r.student_id) AS students 
                                    FROM ratings AS r 
                                    INNER JOIN events AS e ON r.events_id = e.event_id 
                                    GROUP BY e.event_id 
                                    ORDER BY e.date_from DESC 
                                    LIMIT $limit OFFSET $offset";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . htmlspecialchars($row['title']) . "</td>
                                            <td>" . number_format($row['rating'], 1) . " ⭐</td>
                                            <td>" . $row['students'] . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center'>No data available</td></tr>";
                            }
                            ?>
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

<script>
    // Print Button Functionality
    document.getElementById("printBtn").addEventListener("click", function () {
        var printContent = document.getElementById("reportTable").outerHTML;
        var newWin = window.open("", "", "width=800,height=600");
        newWin.document.write("<html><head><title>Print Report</title>");
        newWin.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
        newWin.document.write("</head><body>");
        newWin.document.write("<h2 class='text-center'>Event Ratings Report</h2>");
        newWin.document.write(printContent);
        newWin.document.write("</body></html>");
        newWin.document.close();
        newWin.print();
    });
</script>
