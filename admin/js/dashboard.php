
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
  function fetchNumbers() {
    $.ajax({
      url: "backend/fetch_dashboard_data.php", // PHP script URL
      type: "GET",
      dataType: "json",
      success: function (data) {
        
        $(".counter.text-success").text(data.students_count); // Update Students count
        $(".counter.text-purple").text(data.events_count);   // Update Events count
        $(".counter.text-info").text(data.users_count);      // Update Users count
      },
      error: function () {
        console.log("Failed to fetch data.");
      },
    });
  }

  // Fetch data every 5 seconds
  $(document).ready(function () {
    console.log("data")
    fetchNumbers(); // Fetch on page load
    setInterval(fetchNumbers, 5000); // Refresh every 5 seconds
  });

  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('eventsCalendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                url: '../students/backend/fetch-events.php', // Your backend script
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (Array.isArray(data)) {
                        successCallback(data);
                    } else {
                        console.error('Unexpected response format:', data);
                        failureCallback('Invalid response format');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    alert(`Error: ${textStatus}`);
                    failureCallback(errorThrown);
                }
            });
        },
        eventContent: function (arg) {
            // Customize event display
            let title = arg.event.title;
            let type = arg.event.extendedProps.type;

            // Create a custom element
            let customContent = document.createElement('div');
            customContent.innerHTML = `
                <div>
                    <strong>${title}</strong><br>
                    <small>${type}</small>
                </div>
            `;
            return { domNodes: [customContent] };
        },
        eventClick: function (info) {
            
            window.location.href =  `index.php?link=events&id=${info.event.id}`
        }
    });

    calendar.render();
});

 // Sample Data (Replace with AJAX data if needed)
 $(document).ready(function () {
    function fetchChartData(month, year) {
        $.ajax({
            url: "backend/fetch_event_ratings.php",
            type: "GET",
            data: { month: month, year: year },
            dataType: "json",
            success: function (data) {
                let eventTitles = data.map(event => event.title);
                let eventRatings = data.map(event => parseFloat(event.average_rating));

                let ctx = document.getElementById("myBarChart").getContext("2d");

                // ✅ Check if `window.myBarChart` exists before destroying it
                if (window.myBarChart instanceof Chart) {
                    window.myBarChart.destroy();
                }

                // ✅ Create the bar chart
                window.myBarChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: eventTitles,
                        datasets: [{
                            label: "Average Rating",
                            data: eventRatings,
                            backgroundColor: ["#ff6384", "#36a2eb", "#ffcd56", "#4bc0c0", "#9966ff"],
                            borderColor: "#4e73df",
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5
                            }
                        }
                    }
                });
            },
            error: function () {
                console.log("Error fetching data");
            }
        });
    }

    // ✅ Trigger on month & year selection
    $("#monthSelect, #yearSelect").on("change", function () {
        let selectedMonth = $("#monthSelect").val();
        let selectedYear = $("#yearSelect").val();
        fetchChartData(selectedMonth, selectedYear);
    });

    // ✅ Load the default chart on page load
    fetchChartData($("#monthSelect").val(), $("#yearSelect").val());
});

 </script>
