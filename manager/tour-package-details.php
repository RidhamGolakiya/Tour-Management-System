<?php
session_start();
require_once "../config.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header('location: /login.php');
}
// Check user has manager role or not
else if (isset($_SESSION["role"]) && $_SESSION["role"] != 2) {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the manager site.";
    header('Location: /login.php');
    exit;
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch tour package details from the database based on $id
    $sql = "SELECT * FROM tour_packages WHERE tour_id = $id";
    $result = mysqli_query($con, $sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tour_id = $row['tour_id'];
        $images = $row['images'];
        $name = $row['tour_name'];
        $description = $row['description'];
        $country_name = $row['country_name'];
        $state_name = $row['state_name'];
        $price = $row['price'];
        $formattedPrice = number_format($price, 0, '.', ',');
        $other_details = $row['other_details'];
    } else {
        echo "Tour package not found.";
    }
} else {
    echo "Invalid ID.";
}
$pageTitle = "Tour Packages";
require_once "../components/header.php";
?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="/" class="navbar-brand" style="font-size:30px">
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

        <div>
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
                <div class="d-flex justify-content-between">
                    <h3>Tour Package</h3>
                    <a class="btn btn-outline-secondary" href="./tour-packages.php">Back</a>
                </div>
                <div class="container-fluid" style="padding-top:0">
                    <div class="card mt-3">
                        <div class="pt-0 card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="py-4 fw-bold">Tour Name:</td>
                                            <td class="py-4"><?php echo $name; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Descrition:</td>
                                            <td class="py-4"><?php echo $description; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Country Name:</td>
                                            <td class="py-4"><?php echo $country_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">State Name:</td>
                                            <td class="py-4"><?php echo $state_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Price</td>
                                            <td class="py-4">₹ <?php echo $formattedPrice; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Other Details:</td>
                                            <td class="py-4"><?php echo $other_details; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $imageFilenames = explode(',', $images);

                                            echo '<div id="carousel-' .  $tour_id . '" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-inner">';
                                            foreach ($imageFilenames as $index => $filename) {
                                                $isActive = $index === 0 ? 'active' : '';
                                                echo '<div class="carousel-item text-center ' . $isActive . '">
                                                                <img class="img-fluid" style="max-width:250px" src="../uploads/tours/' . $filename . '" alt="Image ' . $index . '">
                                                              </div>';
                                            }
                                            echo '</div>
                                                      <button class="carousel-control-prev" type="button" data-bs-target="#carousel-' .  $tour_id . '" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                      </button>
                                                      <button class="carousel-control-next" type="button" data-bs-target="#carousel-' .  $tour_id . '" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                      </button>
                                                    </div>';
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./manager.js"></script>
<?php
require_once("../components/footer.php");
?>