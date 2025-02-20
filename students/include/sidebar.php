<style>
    .left-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100vh;
        background: #fff;
        z-index: 1050;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    }

    .sidebar-item .sidebar-link.active {
        background-color: #007bff !important;
        color: #fff !important;
        border-radius: 5px;
    }

    .sidebar-item .sidebar-link.active i {
        color: #fff !important;
    }
</style>

<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($currentPage == 'dashboard') ? 'active' : '' ?>"
                        href="index.php?link=dashboard" aria-expanded="false">
                        <i class="far fa-clock" aria-hidden="true"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- Events -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($currentPage == 'events') ? 'active' : '' ?>"
                        href="index.php?link=events" aria-expanded="false">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span class="hide-menu">Events</span>
                    </a>
                </li>

                <!-- Attendance -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($currentPage == 'attendance') ? 'active' : '' ?>"
                        href="index.php?link=attendance" aria-expanded="false">
                        <i class="fa fa-font" aria-hidden="true"></i>
                        <span class="hide-menu">Attendance</span>
                    </a>
                </li>

                <!-- Profile -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link <?= ($currentPage == 'profile') ? 'active' : '' ?>"
                        href="index.php?link=profile" aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span class="hide-menu">Profile</span>
                    </a>
                </li>

                <!-- Logout -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../logout.php">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>