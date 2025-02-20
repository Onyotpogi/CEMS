<script>
$(document).ready(function () {
    fetchData(); // Load data when the page loads

    function fetchData(page = 1) {
        $.ajax({
            url: "backend/eventReports.php", // Change this to your backend PHP script
            type: "GET",
            data: { page: page },
            dataType: "json",
        })
        .done(function (response) {
            console.log("AJAX Response:", response); // Log response

            $("#data-body").empty(); // Clear previous data
            $("#pagination").empty(); // Clear previous pagination
            
            if (response.data && response.data.length > 0) {
                $.each(response.data, function (index, item) {
                    $("#data-body").append(`
                        <tr>
                            <td>${item.title}</td>
                            <td>${item.rating} ⭐ </td>
                            <td>${item.students}</td>
                        </tr>
                    `);
                });

                // Pagination buttons
                for (let i = 1; i <= response.total_pages; i++) {
                    $("#pagination").append(`
                        <li class="page-item ${i === response.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
                        </li>
                    `);
                }
            } else {
                $("#data-body").append(`<tr><td colspan="3" class="text-center">No Data Available</td></tr>`);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
            console.error("Response Text:", jqXHR.responseText);
            $("#data-body").html(`<tr><td colspan="3" class="text-danger text-center">Error loading data. Please try again.</td></tr>`);
        })
        .always(function () {
            console.log("AJAX request completed");
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    var printBtn = document.getElementById("printBtn");
    var reportTable = document.getElementById("eventReportsTable");

    if (printBtn && reportTable) { 
        printBtn.addEventListener("click", function () {
            var printContent = reportTable.outerHTML;
            var newWin = window.open("", "", "width=800,height=600");

            newWin.document.write(`
                <html>
                <head>
                    <title>Print Report</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                    <style>
                        /* Ensure table styling remains consistent */
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        @media print {
                            body { font-size: 14px; }
                            .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9 !important; }
                        }
                    </style>
                </head>
                <body>
                    <h2 class="text-center">Event Rating Reports</h2>
                    ${printContent}
                </body>
                </html>
            `);

            newWin.document.close();
            newWin.focus();
            newWin.print();
            newWin.close();
        });
    } else {
        console.error("Print button or report table not found!");
    }
});


</script>