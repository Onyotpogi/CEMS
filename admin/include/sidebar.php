

<!-- Left Sidebar -->
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll -->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation -->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($current_page == 'dashboard') ? 'active' : '' ?>"
                        href="index.php?link=dashboard" aria-expanded="false">
                        <i class="far fa-clock" aria-hidden="true"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a> 
                </li>

                <!-- Events -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($current_page == 'events') ? 'active' : '' ?>"
                        href="index.php?link=events" aria-expanded="false">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span class="hide-menu">Events</span>
                    </a>
                </li>

                <!-- Students -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($current_page == 'student') ? 'active' : '' ?>"
                        href="index.php?link=student" aria-expanded="false">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span class="hide-menu">Students</span>
                    </a>
                </li>

                <!-- Attendance -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($current_page == 'attendance') ? 'active' : '' ?>"
                        href="index.php?link=attendance" aria-expanded="false">
                        <i class="fa fa-font" aria-hidden="true"></i>
                        <span class="hide-menu">Attendance</span>
                    </a>
                </li>

                <!-- Reports Dropdown -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                        <span class="hide-menu">Reports</span>
                    </a>
                    <ul class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="index.php?link=event-reports" class="sidebar-link <?= ($current_page == 'event-reports') ? 'active' : '' ?>">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                <span class="hide-menu">Event Reports</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index.php?link=attendance-reports" class="sidebar-link <?= ($current_page == 'attendance-reports') ? 'active' : '' ?>">
                                <i class="fa fa-check-square" aria-hidden="true"></i>
                                <span class="hide-menu">Attendance Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Add-ons Dropdown -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <span class="hide-menu">Add-ons</span>
                    </a>
                    <ul class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="index.php?link=course and year" class="sidebar-link <?= ($current_page == 'course and year') ? 'active' : '' ?>">
                                <i class="fa fa-solid fa-graduation-cap" aria-hidden="true"></i>
                                <span class="hide-menu">Course And Year</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="index.php?link=category" class="sidebar-link <?= ($current_page == 'category') ? 'active' : '' ?>">
                                <i class="fa fa-solid fa-tags" aria-hidden="true"></i>
                                <span class="hide-menu">Category</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="index.php?link=user-logs" aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span class="hide-menu">User Logs</span>
                    </a>
                </li>

                <!-- Logout -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../logout.php"
                        aria-expanded="false">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll -->
</aside>

<!-- CSS for Active Sidebar Item -->
<style>
    .sidebar-item .sidebar-link.active {
        background-color: #007bff !important; /* Change to your preferred color */
        color: #fff !important;
        border-radius: 5px;
    }

    .sidebar-item .sidebar-link.active i {
        color: #fff !important;
    }
</style>
