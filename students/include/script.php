<script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/app-style-switcher.js"></script>
<script src="../plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
<!--Wave Effects -->
<script src="../js/waves.js"></script>
<!--Menu sidebar -->
<script src="../js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="../js/custom.js"></script>
<!--This page JavaScript -->
<!--chartis chart-->
<script src="../plugins/bower_components/chartist/dist/chartist.min.js"></script>
<script src="../plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
<script src="../js/pages/dashboards/dashboard1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Latest Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


<script>
  $(document).ready(function () {
    function loadNotifications() {
        let notificationCount = $("#notificationCount");
        let notificationList = $("#notificationList");
        let student_id = '<?php echo $rowStudent['student_id'];?>';
        $.ajax({
            url: 'backend/get_notifications.php', // Backend PHP file
            method: 'GET',
            data: {studentId: student_id},
            dataType: 'json',
            success: function (response) {
                if (response.length > 0) {
                    notificationList.empty();
                    notificationCount.text(response.length).show(); // Show count when > 0

                    response.forEach(notification => {
                        notificationList.append(`
                            <li>
                                <a class="dropdown-item" href="index.php?link=eventView&id=${notification.event_id}">
                                    <strong>${notification.title}</strong><br>
                                    <span style="font-size: 12px; color: gray;">${notification.date_from}</span>
                                </a>
                            </li>
                        `);
                    });

                    notificationList.append(`
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="index.php?link=events">See all notifications</a></li>
                    `);
                } else {
                    notificationList.html('<li><a class="dropdown-item text-center" href="#">No new notifications</a></li>');
                    notificationCount.hide(); // Hide the badge when count is 0
                }
            }
        });
    }

    // Load notifications on page load
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 3000);
});



</script>
<?php
if($link == 'dashboard'){
    include('js/dashboard.php');
}else if($link == 'eventView'){
    include('js/eventView.php');
}else if($link == 'profile'){
    include('js/profile.php');
}else if($link == 'events'){
    include('js/events.php');
}else if($link == 'attendance'){
    include('js/attendance.php');
}
echo $link;
?>