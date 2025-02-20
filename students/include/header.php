
<style>
.topbar {
    position: relative; /* Ensure it's positioned properly */
    z-index: 1100; /* Higher than the sidebar */
    width: 100%;
}

.navbar-brand {
    z-index: 1101; /* Ensure the brand/logo is also on top */
}

.navbar-collapse {
    z-index: 1100; /* Keep navigation elements on top */
}

.navbar {
    position: fixed; /* Keep navbar always visible */
    top: 0;
    left: 0;
    right: 0;
    background-color: #343a40; /* Dark background for visibility */
}

</style>
<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header d-flex align-items-center justify-content-between" data-logobg="skin6">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <b class="logo-icon">
                    <img src="../plugins/images/cems-logo.png" style="width:50px; height: 50px" alt="homepage" />
                </b>
                <span class="logo-text" style="color: black; text-decoration: none; font-weight: bold;">
                    CEMS
                </span>
            </a>
            <a class="nav-toggler waves-effect waves-light text-dark d-md-none" href="javascript:void(0)">
                <i class="ti-menu ti-close"></i>
            </a>
        </div>



<div class="navbar-collapse collapse d-flex align-items-center justify-content-end" id="navbarSupportedContent" data-navbarbg="skin5">
  <ul class="navbar-nav d-flex align-items-center">
    <!-- Notification Dropdown -->
    <li class="nav-item dropdown me-3">
      <a class="nav-link dropdown-toggle text-white position-relative" href="#" id="notificationDropdown" role="button"
         data-bs-toggle="dropdown" data-bs-display="static" data-bs-offset="0,20" aria-expanded="false">
        <i class="fa fa-bell" style="font-size: 22px;"></i>
        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
              id="notificationCount" style="font-size: 12px; min-width: 18px; display: none;">0</span>
      </a>
      <ul class="dropdown-menu  shadow border-0 p-2 rounded-3"
          aria-labelledby="notificationDropdown" id="notificationList"
          style="width: 350px; max-height: 350px; overflow-y: auto;">
        <li class="dropdown-header text-center fw-bold text-primary bg-light p-2 rounded">
          🔔 Notifications
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-center text-muted" href="#">No new notifications</a></li>
      </ul>
    </li>

    <!-- User Profile -->
    <li class="nav-item">
      <a class="profile-pic d-flex align-items-center text-white text-decoration-none" href="#">
        <img src="../plugins/images/users/varun.jpg" alt="user-img" width="36" class="rounded-circle">
        <span class="font-medium ms-2"><?= htmlspecialchars($rowUser['name']) ?></span>
      </a>
    </li>
  </ul>
</div>

    </nav>
</header>
