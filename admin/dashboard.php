<?php
session_start();
require_once "../config.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
  $_SESSION['success'] = false;
  $_SESSION['message'] = "Authentication failed";
  header("location: $appUrl/login.php");
}
// Check user has admin role or not
else if (isset($_SESSION["role"]) && $_SESSION["role"] != 1) {
  setcookie('user', '', time() - 3600, '/');
  $_SESSION['success'] = false;
  $_SESSION['message'] = "You are not authorized to access the admin site.";
  header("location: $appUrl/login.php");
  exit;
}
$pageTitle = "Dashboard";
require_once "../components/header.php";

// Count Data to show at dashboard
try {
  $users = "SELECT COUNT(*) AS total_users FROM users where user_id != 1 and role = 0";
  $managers = "SELECT COUNT(*) AS total_managers FROM users where user_id != 1 and role = 2";
  $packages = "SELECT COUNT(*) AS total_packages FROM tour_packages";
  $booked_packages = "SELECT COUNT(*) AS total_booking FROM transactions WHERE payment_status = 'Completed' OR payment_type = 0";
  $total_blogs = "SELECT COUNT(*) AS total_blogs FROM blogs";
  $total_enquiries = "SELECT COUNT(*) AS total_enquiries FROM enquiries";
  $total_earnings = "SELECT SUM(CAST(REPLACE(t.amount, ',', '') AS SIGNED)) AS total_earnings FROM transactions as t, packageBooking as p where t.id = p.transaction_id";
  $result = mysqli_query($con, $users);
  $result6 = mysqli_query($con, $managers);
  $result1 = mysqli_query($con, $packages);
  $result2 = mysqli_query($con, $booked_packages);
  $result3 = mysqli_query($con, $total_blogs);
  $result4 = mysqli_query($con, $total_enquiries);
  $result5 = mysqli_query($con, $total_earnings);

  $totalUsers = 0;
  $totalManagers = 0;
  $totalPackages = 0;
  $totalBookedPackages = 0;
  $totalBlogs = 0;
  $totalEnquiries = 0;
  $totalEarnings = 0;
  if ($result && $result->num_rows > 0 && $result1 && $result1->num_rows > 0 && $result2 && $result2->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalUsers = $row['total_users'];
    $row6 = $result6->fetch_assoc();
    $totalManagers = $row6['total_managers'];
    $row1 = $result1->fetch_assoc();
    $totalPackages = $row1['total_packages'];
    $row2 = $result2->fetch_assoc();
    $totalBookedPackages = $row2['total_booking'];
    $row3 = $result3->fetch_assoc();
    $totalBlogs = $row3['total_blogs'];
    $row4 = $result4->fetch_assoc();
    $totalEnquiries = $row4['total_enquiries'];
    $row5 = $result5->fetch_assoc();
    $totalEarnings = number_format($row5['total_earnings'] > 0 ? $row5['total_earnings'] : 0, 0, '.', ',');
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <!-- Sidebar Start -->
  <aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="<?php echo $appUrl;?>" class="navbar-brand" style="font-size:30px">
          <div class="d-flex align-items-center"><img src="../uploads/settings/<?php echo $_SESSION['logo'] ?>" class="img-fluid" alt="logo" width="50" height="50"><span class="mx-2 my-1" style="font-size:20px"><?php echo $_SESSION['site_name'] ?></span></div>
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="fa fa-times"></i>
        </div>
      </div>
      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <div class="sidebar">
          <ul id="sideNav">
          </ul>
        </div>
      </nav>
    </div>
  </aside>
  <!--  Sidebar End -->
  <!--  Main wrapper -->
  <div class="body-wrapper">
    <!--  Header Start -->
    <?php require_once "../components/profileHeader.php" ?>
    <!--  Header End -->
    <div class="p-5">
      <?php
      if (isset($_SESSION['message']) && isset($_SESSION['success'])) {
        $message = $_SESSION['message'];
        $success = $_SESSION['success'];
        $toastType = $success ? 'success' : 'error';
        echo "<script>
          toastr.options = {
            positionClass: 'toast-top-right',
            timeOut: 2000,
            progressBar: true,
          }; toastr.$toastType('$message');</script>";
        unset($_SESSION['message']);
        unset($_SESSION['success']);
      }
      ?>
      <div>
        <h3>Dashboard</h3>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mt-md-0 mt-sm-0 mt-3">
        <a href="./users.php">
          <div class="bg-warning cursor-pointer gradient-style1 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Users</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalUsers; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-users" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mt-md-0 mt-sm-0 mt-3 mb-3">
        <a href="./managers.php">
          <div class="bg-warning cursor-pointer gradient-style7 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Managers</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalManagers; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-user" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mb-3">
        <a href="./tour-packages.php">
          <div class="bg-warning cursor-pointer gradient-style2 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Tour Packages</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalPackages; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-cubes" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mb-3">
        <a href="./blogs.php">
          <div class="bg-warning cursor-pointer gradient-style3 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Blogs</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalBlogs; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-paste" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mb-3">
        <a href="./booking.php">
          <div class="bg-warning cursor-pointer gradient-style4 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 19px;line-height: 1.5em;">Booked Packages</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalBookedPackages; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-box-archive" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mb-3">
        <a href="./enquiries.php">
          <div class="bg-warning cursor-pointer gradient-style5 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Enquiries</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalEnquiries; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-circle-question" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mb-3">
        <a href="./booking.php">
          <div class="bg-warning cursor-pointer gradient-style6 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Total Earnings</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;">₹ <?php echo $totalEarnings; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-money-bill" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<script src="./admin.js"></script>
<?php
require_once "../components/footer.php";
?>