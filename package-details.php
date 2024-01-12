<?php
session_start();
require_once "config.php";

if (isset($_GET['packageId'])) {
    $tour_id = $_GET['packageId'];
    $tour_package = "select * from tour_packages where tour_id = $tour_id";
    $result = mysqli_query($con, $tour_package);
    $row = mysqli_fetch_array($result);
    $name = $row['tour_name'];
    $price = $row['price'];
    $formattedPrice = number_format($price, 2, '.', ',');
    $images = $row['images'];
    $pageTitle = $name;
} else {
    header("Location: $appUrl/packages.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <title><?php echo $pageTitle . " | " . $_SESSION['site_name'] ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/header.css" />
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
    <script src="./assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="./assets/css/style.css">
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
    </style>
</head>

<body>
    <?php
    require_once "./components/navbarHome.php";
    ?>

    <div style="background:url('./uploads/tours/<?php echo $images; ?>');padding:5% 0;background-repeat: no-repeat;background-size: cover;">
        <div class="container text-center ">
            <h1 class="custom-text"><?php echo $name; ?></h1>
            <p class="book-now">
                <?php
                if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] != 1  && $_SESSION['role'] != 2) {
                    echo '<a href="#" class="default-btn" data-bs-toggle="modal" data-bs-target="#packageModal"><span>Book Now</span><span></a>';
                } else if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
                    echo '<a href="/admin/dashboard.php" class="default-btn"><span>Go to Admin Panel</span><span></a>';
                } else if (isset($_SESSION['role']) && $_SESSION['role'] == 2) {
                    echo '<a href="/manager/dashboard.php" class="default-btn"><span>Go to Manager Panel</span><span></a>';
                } else {
                    echo '<a href="./login.php" class="default-btn"><span>Login for Booking</span><span></a>';
                }
                ?>
                </a>
            </p>
        </div>
    </div>

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

    <!-- Footer -->
    <?php
    include_once "./components/footerHome.php";
    ?>

    <script>
        // $(document).ready(function() {
        //     $("form").submit(function(e) {
        //         e.preventDefault();

        //         var name = $("#name").val();
        //         var phone_no = $("#phone_no").val();
        //         var total_person = $("#total_person").val();
        //         var message = $("#msg").val();

        //         if (name.trim() === "") {
        //             $("#validateMsg").text("Please enter a name");
        //             return false;
        //         }

        //         if (phone_no.trim() === "") {
        //             $("#validateMsg").text("Please enter a phone number");
        //             return false;
        //         } else if (phone_no.length != 10) {
        //             $("#validateMsg").text("Please enter a phone number with 10 digits.");
        //             return false;
        //         }

        //         if (isNaN(total_person) || total_person <= 0) {
        //             $("#validateMsg").text("Please enter a total person");
        //             return false;
        //         }

        //         if (message.trim() === "") {
        //             $("#validateMsg").text("Please enter a message");
        //             return false;
        //         }

        //         this.submit();
        //     });
        // });

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

    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <script src="./assets/js/app.min.js"></script>
    <script src="./assets/font-awesome/all.min.js"></script>
    <script src="./assets/js/script.js"></script>
</body>

</html>