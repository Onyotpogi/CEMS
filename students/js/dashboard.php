<script>
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
                url: 'backend/fetch-events.php', // Your backend script
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
            
            window.location.href =  `index.php?link=eventView&id=${info.event.id}`
        }
    });

    calendar.render();
});

</script>