<?php
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $role = "admin";
} else if (isset($_SESSION['role']) && $_SESSION['role'] == 2) {
    $role = "manager";
} else {
    $role = "user";
}
?>

<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                <span class="navbar-toggler-icon"></span>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li> <a href="/index.php" title="Go to Home Page" class="text-black home p-1 d-flex justify-content-end"><i class="fa fa-home"></i></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img width=35 height=35 id="profileImage" class="rounded-circle img-fluid mx-2" src="" alt="Profile Image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="/<?php echo $role; ?>/edit-profile.php" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="fa fa-user"></i>
                                <p class="mb-0 fs-3">My Profile</p>
                            </a>
                            <button class="d-flex align-items-center gap-2 dropdown-item" id="logoutButton">
                                <i class="fa fa-sign-out"></i>
                                <p class="mb-0 fs-3">Logout</p>
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>