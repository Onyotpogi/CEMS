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
<!-- Latest Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<?php
if($link == 'dashboard'){
    include('js/dashboard.php');
}else if($link == 'events'){
    include('js/events.php');
}else if($link == 'attendance'){
    include('js/attendance.php');
}else if($link == 'student'){
    include('js/student.php');
}else if($link == 'event-reports'){
    include('js/eventReports.php');
}else if($link == 'attendance-reports'){
    include('js/attendanceReports.php');
}else if($link == 'course and year'){
    include('js/courseYear.php');
}else if($link == 'category'){
    include('js/category.php');
}else if($link == 'user-logs'){
    include('js/userLogs.php');
}
?>