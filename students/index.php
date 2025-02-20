<?php
    include('include/config.php');
    $link = $_GET['link'];
    if($link == 'dashboard'){
        include('include/authProfile.php');
    }else if($link == 'eventView'){
        include('include/authProfile.php');
    }else if($link == 'profile'){
        include('include/auth.php');
    }else if($link == 'events'){
        include('include/authProfile.php');
    }else if($link == 'attendance'){
        include('include/authProfile.php');
    }
    include('include/north.php');
?>
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <?php
                    if($link == 'dashboard'){
                        include('view/dashboard.php');
                    }else if($link == 'eventView'){
                        include('view/eventsView.php');
                    }else if($link == 'events'){   
                        include('view/events.php');
                    }else if($link == 'profile'){   
                        include('view/profile.php');
                    }else if($link == 'attendance'){   
                        include('view/attendance.php');
                    }
                ?>
                
               
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
<?php
    include('include/south.php')
?>