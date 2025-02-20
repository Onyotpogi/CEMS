<?php
    include('include/config.php');
    include('include/auth.php');
    include('include/north.php');
    $link = $_GET['link']
?>
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <?php
                    if($link == 'dashboard'){
                        include('view/dashboard.php');
                    }else if($link == 'events'){
                        if(!EMPTY($_GET['id'])){
                            include('view/eventsView.php');
                        }else{
                            include('view/events.php');
                        }
                    }else if($link == 'attendance'){
                        include('view/attendance.php');
                    }else if($link == 'student'){
                        include('view/student.php');
                    }else if($link == 'event-reports'){
                        include('view/eventsReport.php');
                    }else if($link == 'attendance-reports'){
                        include('view/attendanceReports.php');
                    }else if($link == 'course and year'){
                        include('view/courseYear.php');
                    }else if($link == 'category'){
                        include('view/category.php');
                    }else if($link == 'user-logs'){
                        include('view/userLogs.php');
                    }
                ?>
                
               
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
<?php
    include('include/south.php')
?>