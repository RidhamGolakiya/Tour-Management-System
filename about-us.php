<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <title>About Us | <?php echo $_SESSION['site_name'] ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/about.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css">
    <?php
    include_once "./storeSetting.php";
    ?>
</head>

<body style="overflow-x:hidden">
    <!-- header -->
    <?php
    require_once "./components/navbarHome.php";
    ?>

    <div class="aboutUs-content" style="background-image: url('./assets//images/web-images/about-image.jpg');background-repeat: no-repeat;background-size: cover;">
        <div class="text-center  pt-5" style="background-color: rgb(32, 10, 10); opacity: 0.55; height:300px; ">
            <h1 style="font-family: unset; color:white;">About Us</h1>
            <h3 style="font-family: 'Times New Roman', Times, serif; color:white;" class="pt-2"><a href="./index.php" style="color:white; text-decoration: none;">Home</a>&nbsp; >> &nbsp;About Us</h3>
        </div>
    </div>

    <div class="row d-flex justify-content-evenly" style="margin-top:100px;">
        <div class="col-md-5" style="font-family: math; font-size: x-large;">
            <div style="margin-left: 50px; color: #0f2454;">
                <p>You can choose any country with good tourism. Agency elementum sesue the aucan vestibulum aliquam
                    justo in sapien rutrum volutpat. Donec in quis the pellentesque velit. Donec id velit ac arcu
                    posuere blane.</p>
                <p class="mt-4">Are you ready for an adventure of a lifetime? Look no further! Our Tourism System is
                    your gateway to discovering the beauty and wonder of the world.</p>
                <p class="mt-4">Tourism systems allow travelers to book accommodations, tours, and activities
                    seamlessly. This not only simplifies the planning process for tourists but also helps hotels and
                    tour operators manage their inventory more efficiently.</p>
                <div class="d-flex ">
                    <i class="fa fa-check-circle mx-2"></i>
                    <p>Traveling With Best Experience...</p>
                </div>
                <div class="d-flex ">
                    <i class="fa fa-check-circle mx-2"></i>
                    <p>Adventures Are The Important Part Of The Life...</p>
                </div>
                <div class="d-flex">
                    <i class="fa fa-phone-volume fa-2x me-4 align-self-center"></i>
                    <div>
                        <p>call info</p>
                        <h5>9874563210</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 ps-4">
            <img src="./assets/images/2.jpeg" class="img-fluid" alt="">
        </div>
    </div>

    <div class="main-content" style="background-image: url('./assets/images/web-images/img1.webp'); opacity:88; margin-top:-30px;">
        <div style="background-color:#0f2454; opacity:0.60; height:300px;">
            <div class="content" style="color: rgb(255, 255, 255); padding-top:90px; ">
                <h4>THE BEST TRAVEL AGENCY</h4>
                <h1>WE HELPING YOU FIND
                    <br><span style="color: rgb(255, 255, 255)">YOUR DREAM</span> VACATION
                </h1>
            </div>
        </div>
    </div>

    <div style="background-color: white; padding-top: 100px; padding-bottom: 85px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-subtitle">
                        TRAVEL EXPERTS
                    </div>
                    <div class="section-title">MEET OUR <span style="color: #0f2454;">GUIDES</span></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center">
                        <img src="./assets/images/web-images/i1.jpg" alt="Emily Davis" class="img-fluid">
                        <h1 class="mt-4" style="color: #0f2454;">Emily Davis</h1>
                        <h3 style="color: #0f2454;">Switzerland Guide</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <img src="./assets/images/web-images/i2.jpg" alt="John Smith" class="img-fluid">
                        <h1 class="mt-4" style="color: #0f2454;">John Smith</h1>
                        <h3 style="color: #0f2454;">Maldives Guide</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <img src="./assets/images/web-images/i3.jpg" alt="Milie Johnson" class="img-fluid">
                        <h1 class="mt-4" style="color: #0f2454;">Milie Johnson</h1>
                        <h3 style="color: #0f2454;">Greece Guide</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="about-content2" style="background-image: url('./assets/images/img2.jpg');">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center mb-4 mb-md-0">
                    <h3 class="text-white">We Provide Top Destinations Especially For You. Book Now and Enjoy!</h3>
                </div>
                <div class="col-md-6">
                    <div class="bg-white p-3 rounded-5">
                        <div class="d-flex align-items-center">
                            <img src="./assets/images/about-us.jpeg" alt="" class="img-thumbnail border-primary rounded-circle me-3" style="width: 100px; height: 100px;">
                            <div>
                                <h5 class="mb-0">Ashvini Sharma</h5>
                                <div>
                                    <span class="text-primary">&#9733;</span>
                                    <span class="text-primary">&#9733;</span>
                                    <span class="text-primary">&#9733;</span>
                                    <span class="text-primary">&#9733;</span>
                                    <span class="text-primary">&#9734;</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 patient-review">
                            Clarity and transparency of billing and financial matters. Accessibility and responsiveness of the emergency department. Quality of follow-up care and support after discharge. Quality of care during emergency situations.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    include_once "./components/footerHome.php";
    ?>
</body>

</html>