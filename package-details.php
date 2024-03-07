<?php
session_start();
require_once "config.php";

if (isset($_GET['packageId'])) {
    $tour_id = $_GET['packageId'];
    $tour_package = "SELECT * FROM tour_packages WHERE tour_id = $tour_id";
    $result = mysqli_query($con, $tour_package);
    $row = mysqli_fetch_array($result);
    $name = $row['tour_name'];
    $price = $row['price'];
    $formattedPrice = number_format($price, 2, '.', ',');
    $images = $row['images'];
    $pageTitle = $name;
} else {
    header("Location: $appUrl/packages.php");
    exit(); // Ensure script stops after redirecting
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle . " | " . $_SESSION['site_name'] ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css">
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <style>
        .carousel-container {
            position: relative;
        }
        .carousel {
            z-index: 0;
        }
        .content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1;
        }
        .content {
            background-color: rgba(0, 0, 0, 0.5); /* Adjust opacity as needed */
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php require_once "./components/navbarHome.php"; ?>

    <!-- Carousel Container -->
    <div class="carousel-container">
        <!-- Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                // Split the images string into an array
                $imageArray = explode(",", $images);
                // Loop through each image to generate carousel indicators
                foreach ($imageArray as $key => $image) {
                    $activeClass = $key == 0 ? 'active' : ''; // Add 'active' class to the first indicator
                    echo "<li data-bs-target='#carouselExampleIndicators' data-bs-slide-to='$key' class='$activeClass'></li>";
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                // Loop through each image to generate carousel items
                foreach ($imageArray as $key => $image) {
                    $activeClass = $key == 0 ? 'active' : ''; // Add 'active' class to the first item
                    echo "<div class='carousel-item $activeClass'>";
                    echo "<img src='./uploads/tours/$image' class='d-block w-100' alt=''>";
                    echo "</div>";
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <!-- End of Carousel -->

        <!-- Content Overlay -->
        <div class="content-overlay">
            <div class="content">
                <h1 class="custom-text"><?php echo $name; ?></h1>
                <p class="book-now">
                    <?php
                    if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
                        echo '<a href="#" class="default-btn" data-bs-toggle="modal" data-bs-target="#packageModal"><span>Book Now</span><span></a>';
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
                        echo "<a href='$appUrl/admin/dashboard.php' class='default-btn'><span>Go to Admin Panel</span><span></a>";
                    } else if (isset($_SESSION['role']) && $_SESSION['role'] == 2) {
                        echo "<a href='$appUrl/manager/dashboard.php' class='default-btn'><span>Go to Manager Panel</span><span></a>";
                    } else {
                        echo '<a href="./login.php" class="default-btn"><span>Login for Booking</span><span></a>';
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- End of Content Overlay -->
    </div>
    <!-- End of Carousel Container -->

    <!-- Additional Content Below Carousel -->
    <div class="container mt-5">
        <?php echo $row['other_details']; ?>
    </div>

    <?php include_once "./components/footerHome.php"; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/script.js"></script>
</body>
</html>
