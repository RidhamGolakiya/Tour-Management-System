<?php
$current_url = $_SERVER['REQUEST_URI'];

include_once ("./config.php");

$menu_items = array(
    "$appUrl"=> 'Home',
    // '/about-us.php' => 'About us',
    "$appUrl/packages.php" => 'Packages',
    "$appUrl/blog.php" => 'Blogs',
    "$appUrl/contact.php" => 'Contact us',
);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
  <a class="navbar-brand" href=""><img src="./uploads/settings/<?php echo $_SESSION['logo']; ?>" height="70px" width="100px" style="border-radius: 100%;"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="display:flex;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($menu_items as $url => $title) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_url == $url) ? 'active_nav' : ''; ?>" href="<?php echo $url; ?>"><?php echo $title; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
      <div style="display:flex; flex-direction: row;">
            <ul style="text-decoration: none; list-style-type: none;" class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
                    $role = "$appUrl/admin/dashboard.php";
                } else if (isset($_SESSION['role']) && $_SESSION['role'] == 2) {
                    $role = "$appUrl/manager/dashboard.php";
                } else {
                    $role = "$appUrl/user/dashboard.php";
                }

                if (isset($_SESSION['role'])) {
                    echo ' <li class="nav-item"> <a class="nav-link" href="' . $role . '">Dashboard</a>  </li>';
                } else {
                    echo '
                        <li class="nav-item">
                        <a class="nav-link" href="./login.php">Log in</a>
                    </li><li class="nav-item">
                        <a class="nav-link" href="./register.php">Sign up</a>
                    </li>
                    ';
                }
                ?>
            </ul>
        </div>
    </div>
  </div>
</nav>