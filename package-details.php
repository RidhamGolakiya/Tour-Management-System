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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <script src="./assets/toastr/toastr.min.js"></script>
    <style>

        .tool_tip:hover .pricetooltip {
            display: block;
        }

        .pricetooltip {
            display: none;
            background: black !important;
            margin-left: 28px;
            padding: 10px;
            position: absolute;
            z-index: 1000;
            width: 200px;
            font-size: 12px;
        }

        .tool_tip {
            background: black !important;
            color: white !important;
            border-radius: 48%;
            padding: 0px 4px;
            text-align: center;
        }
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

    <div class="container-fluid py-0">
        <div class="modal fade" id="packageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content custom-radius">
                    <div class="text-center">
                        <div>
                            <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <h3 class="modal-title"><?php echo $name; ?></h3>
                        </div>
                        <h2 class="custom-price" style="margin-top: 15px;font-size: 30px;">₹<?php echo $formattedPrice; ?> <small>/ Per Person</small></h2>
                    </div>
                    <hr>
                    <form autocomplete="off" action="./queries.php" method="POST">
                        <div class="modal-body p-4 row">
                            <input type="hidden" name="package_id" value="<?php echo $tour_id; ?>">
                            <input type="hidden" name="package_price" value="<?php echo $price; ?>">
                            <input type="hidden" name="package_name" value="<?php echo $name; ?>">
                            <input type="hidden" name="total_price" id="grand_total">
                            <div class="col-md-6">
                                <label>Total Person: </label><span class="text-danger">*</span><span class="mx-1 tool_tip">?<span class="pricetooltip">Price will be multiply by total person you entered.</span></span>
                                <input type="number" name="total_person" id="total_person" min="0" class="form-control mt-2 mb-3" placeholder="Total Person">
                            </div>
                            <div class="col-md-6">
                                <label>Message: </label><span class="text-danger">*</span>
                                <textarea class="form-control mt-2 mb-3" aria-invalid="false" name="message" id="msg" placeholder="Enter your message"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Payment Method:</label>
                                <select name="payment_method" id="payment_method" class="form-control mt-2 mb-3">
                                    <option value="manual">Manually</option>
                                    <option value="strp">Pay with Stripe</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Total Price:</label>
                                <label class="fw-bolder" id="total_price"></label>
                            </div>
                            <span id="validateMsg" class="text-danger"></span>
                            <div class="col-md-12 mt-4 d-flex justify-content-center">
                                <!-- <button name="payment" id="book" class="btn default-btn">Book</button> -->
                                <button type="submit" name="payment" value="btnPayment" class="btn default-btn">Pay Now <span class="fa fa-angle-right"></span></button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
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
        $(document).ready(function() {
            function updateTotalPrice() {
                var totalPersonInput = $("#total_person").val();
                var price = <?php echo $price; ?>

                // Check if the input is empty or not a valid number
                if (totalPersonInput === "" || isNaN(totalPersonInput) || totalPersonInput < 0) {
                    totalPersonInput = 0;
                }

                const formattedPrice = new Intl.NumberFormat("en-IN").format(price * totalPersonInput);
                $("#total_price").text(`₹ ${formattedPrice}`);
                $("#grand_total").val(formattedPrice);
            }

            // Update total price when input changes
            $("#total_person").on("input", updateTotalPrice);

            updateTotalPrice();
        });
    </script>



    <script src="./assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>