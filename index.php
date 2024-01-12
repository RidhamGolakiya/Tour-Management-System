<?php
session_start();
include_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | <?php echo $_SESSION['site_name'] ?></title>
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css">
    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
    <?php
    include_once "./storeSetting.php";
    ?>
    <script>
        $(document).ready(function() {
            let setting = localStorage.getItem("settings");
            setting = setting && JSON.parse(setting);
            document.documentElement.style.setProperty("--primary", setting.themeColor);
        })
    </script>
</head>

<body>
    <!-- header -->
    <?php
    require_once "./components/navbarHome.php";
    ?>

    <div style="position: relative; width: 100%; height: 100vh; overflow: hidden;">
        <video autoplay loop muted playsinline style="position: absolute; top: 0; left: 0;">
            <source src="./assets/images/video2.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <span class="text-center span">Make Your Life Extraordinary With Us</span>
            <p class="text-white text-center" style="font-size: 15px">Welcome to the world of tourism management, where we explore<br> the fascinating industry that connects people and places,<br> creating unforgettable experiences.
            </p>
        </div>
    </div>


    <div class="text-center mt-5">
        <h2 style="font-family: 'Times New Roman', Times, serif;">The Best Choice</h2>
        <h1 class="mt-3" style="font-family: 'Times New Roman', Times, serif; font-weight: bolder;">Our Popular Places
        </h1>
        <h5 class="mt-5 ps-3 pe-3" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Tourism is the largest and
            fastest-growing industry across the world. It is a source of revenue and employment. Tourism is a social, cultural and economic phenomenon which entails the movement of people to places outside their usual environment for personal purposes. Tourism is travel for pleasure or business, and the commercial activity of providing and supporting such travel.</h5>
    </div>
    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-evenly">
            <?php
            include_once "config.php";

            $query = "SELECT c.name as country, s.name as state,t.* FROM tour_packages as t,countries as c,states as s where s.id = t.state_name and c.id = t.country_name ORDER BY t.created_at DESC LIMIT 4";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $package_id = $row['tour_id'];
                    $image = $row['images'];
                    $countryOrCity = $row['country'] ? $row['country'] : $row['state'];
                    $description = $row['description'];

                    // Truncate the description to a reasonable length
                    $shortDescription = substr($description, 0, 100) . (strlen($description) > 100 ? '...' : '');
            ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card">
                            <img src="./uploads/tours/<?php echo $image; ?>" alt="<?php echo $countryOrCity; ?>" class="card-img-top w-100" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $countryOrCity; ?></h5>
                                <p class="card-text"><?php echo $shortDescription; ?></p>
                                <a href="package-details.php?packageId=<?php echo $package_id; ?>" class="btn btn-outline-secondary">Know More</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="text-center">No packages found. Check back later for more options.</p>';
            }

            mysqli_close($con);
            ?>
        </div>

    </div>
    <?php if (mysqli_num_rows($result) > 0) : ?>
        <div class="row justify-content-center my-4">
            <div class="col-auto">
                <a href="packages.php" class="btn btn-outline-secondary">Browse All</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="home-content2" style="background-image: url('./assets/images/web-images/destination.jpg');">
        <div class="text-center" style="background-color: #2b2b4f; opacity: 0.88; padding: 60px 0;">
            <h1 class="text-white" style="font-family: 'Times New Roman', Times, serif; font-weight: bolder;">Explore Your Dream Destinations</h1>
            <p class="mt-4 text-white" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 18px;">Discover the world's most captivating tourist destinations, from pristine natural wonders to rich cultural heritage.</p>
            <p class="text-white" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 18px;">Plan your next adventure with us and create unforgettable memories.</p>
            <p class="text-white" style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 18px;">Start your journey now!</p>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="pt-md-5" style="font-family: 'Times New Roman', Times, serif; font-weight: bolder;">Journey To The Skies Made Simple!</h1>
                <h5 class="mt-4 ps-4 pe-4" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Traveling is a wonderful way to explore new places, learn about different cultures, and gain unique experiences.</h5>
            </div>
        </div>

        <div class="row text-center mt-5 pb-5">
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="home-map" style="border: 5px; box-shadow: 13px 14px 20px -4px gray; padding: 30px; background-color: #f2f2f2;">
                    <img src="./assets/images/web-images/map.png" alt="" height="35px" width="35px">
                    <h5 class="mt-4 ps-4 pe-4" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Find Your Destination</h5>
                </div>
            </div>

            <div class="col-md-4 mt-3 mt-md-0">
                <div class="home-ticket" style="border: 5px; box-shadow: 13px 14px 20px -4px gray; padding: 30px; background-color: #f2f2f2;">
                    <img src="./assets/images/web-images/ticket.png" alt="" height="35px" width="35px">
                    <h5 class="mt-4 ps-4 pe-4" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Book A Tour Ticket</h5>
                </div>
            </div>

            <div class="col-md-4 mt-3 mt-md-0">
                <div class="home-payment" style="border: 5px; box-shadow: 13px 14px 20px -4px gray; padding: 30px; background-color: #f2f2f2;">
                    <img src="./assets/images/web-images/payment-method.png" alt="" height="35px" width="35px">
                    <h5 class="mt-4 ps-4 pe-4" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Pay & Start Journey</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row home-mountains" style="background-image: url('./assets/images/web-images/mountains.jpg'); margin-top: 15px;">
        <div class="col-md-12" style="background-color: rgba(43, 43, 79, 0.88); height: 400px;">
            <h5 class="ps-4 pt-5 text-white" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">- Introducing Travel Adventures</h5>
            <h1 class="mt-3 ps-4 text-white" style="font-family: 'Times New Roman', Times, serif; font-weight: bolder;">
                Hosted Journeys To Extraordinary Places
            </h1>
            <h5 class="mt-5 ps-4 pe-3 text-white" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Adventures are full of fun and enjoyable activities. They help us build strength and make our lives more exciting.</h5>
            <button class="btn btn-warning btn-lg ms-4 mt-4">Get More Details</button>
        </div>
    </div>

    <!-- footer -->
    <?php
    include_once "./components/footerHome.php";
    ?>
</body>

</html>