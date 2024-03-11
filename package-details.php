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
    <script src="./assets/toastr/toastr.min.js"></script>
    <style>
        /* Custom CSS */
        .carousel-container {
            position: relative;
            width: 100%;
            max-width: 1200px;
            /* Adjust the maximum width as needed */
            margin: 0 auto;
        }

        .carousel {
            z-index: 0;
        }

        .carousel-item img {
            max-height: 500px;
            /* Adjust the maximum height of the images */
            object-fit: cover;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            /* Adjust the width of the control buttons */
            color: #fff;
            background: none;
            border: none;
            outline: none;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }

        .content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .content {
            text-align: center;
        }

        .custom-text {
            font-size: 3rem;
            color: #fff;
        }

        .book-now {
            margin-top: 20px;
            position: relative;
            /* Add this line */
            z-index: 2;
            /* Add this line */
        }

        .default-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            position: relative;
            /* Add this line */
            z-index: 2;
            /* Add this line */
        }

        .default-btn:hover {
            background-color: #0056b3;
        }


        /* Media Query for smaller screens */
        @media (max-width: 768px) {
            .custom-text {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <?php require_once "./components/navbarHome.php"; ?>

    <!-- Carousel Container -->
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
    <div class="container mt-5">
        <?php echo $row['other_details']; ?>
    </div>

    <?php include_once "./components/footerHome.php"; ?>
    <script>
        $(document).ready(function() {
            // Handle carousel navigation
            $('.carousel-control-prev').click(function() {
                $('#carouselExampleIndicators').carousel('prev');
            });

            $('.carousel-control-next').click(function() {
                $('#carouselExampleIndicators').carousel('next');
            });
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="./assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>