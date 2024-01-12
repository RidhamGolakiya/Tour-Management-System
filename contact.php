<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <script src="./assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="./assets/css/contact.css">
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <script src="./assets/toastr/toastr.min.js"></script>
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css">
    <title>Contact Us | <?php echo $_SESSION['site_name'] ?></title>
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <?php
    include_once "./storeSetting.php";
    ?>
</head>

<body style="overflow-x: hidden;">
    <!-- header -->
    <?php
    require_once "./components/navbarHome.php";
    ?>

    <div class="contact-content" style="background-image: url('./assets//images/web-images/contact.jpg');">
        <div class="text-center  pt-5" style="background-color: rgb(32, 10, 10); opacity: 0.55; height:300px; ">
            <h1 style="font-family: unset; color:white;">Contact Us</h1>
            <h3 style="font-family: 'Times New Roman', Times, serif; color:white;" class="pt-2"><a href="/" style="color:white; text-decoration: none;">Home</a>&nbsp; >> &nbsp;Contact Us</h3>
        </div>
    </div>

    <div class="contact">
        <div class="container-fluid p-5">
            <div class="row">
                <div class="col-md-6">
                    <h1 style="border-bottom: 5px solid #cdad4c; font-size: 40px;">Let's Connect with Us...</h1>
                    <p class="mt-4" style="font-size: larger;">
                        <span class="Ctext">We</span> love to hear about your ideas and the challenges that you have.
                        Let's discuss it together and bring your idea into existence. Please share your valuable and
                        important time with us to help us improve our systems. We'll provide the best information for
                        our systems.
                    </p>
                </div>
                <div class="col-md-6">
                    <img src="./assets/images/web-images/contact2.jpg" class="img-fluid" alt="Contact Image 1">
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-5" style="background-color: #f2f2f2;">
        <h1 class="ps-5 mb-4" style="border-bottom: 5px solid #cdad4c; font-size: 30px;">Leave a Message</h1>
        <div class="row d-flex justify-content-evenly">
            <div class="col-lg-5 ps-2">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235014.2579225221!2d72.43965489283016!3d23.020181762126324!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1676378468849!5m2!1sen!2sin" width="100%" height="500" class="mt-5" style="border: 0; box-shadow: 13px 14px 20px -4px gray;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-4">
                <div id="frm">
                    <div class="form-goup mt-4">
                        <label for="Name"></label>
                        <input type="text" id="Name" class="form-control p-3" name="name" placeholder="Enter your name">
                    </div>
                    <div class="form-group mt-3">
                        <label for="email"></label>
                        <input type="email" id="email" class="form-control p-3" name="email" placeholder="Enter your email">
                    </div>
                    <div class="form-group mt-3">
                        <label for="Mobile"></label>
                        <input type="tel" id="Mobile" class="form-control p-3" name="mobile" placeholder="Enter your mobile number">
                    </div>
                    <div class="form-group mt-3">
                        <label for="Message"></label>
                        <textarea class="form-control p-3" id="Message" name="message" cols="40" rows="7" placeholder="Enter your message"></textarea>
                    </div>
                    <div id="err" class="ps-5 pt-3"></div>
                    <button type="submit" class="btn btn-outline-dark btn-md mt-3" id="Submit" name="btnSubmit" value="Submit" style="box-shadow: 13px 14px 20px -4px gray;">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
    include_once "./components/footerHome.php";
    ?>
    <script>
        function contact() {
            let name = document.getElementById('Name').value;
            let mobile = document.getElementById('Mobile').value;
            let message = document.getElementById('Message').value;

            var nFormat = /^[A-Z a-z]+$/;

            if (name.match(nFormat) && mobile.length == 10 && !isNaN(mobile) && message != "") {
                document.getElementById('err').innerHTML = "Your data submitted successfully.";
                document.getElementById('err').style.color = 'green';
                return true;
            } else {
                document.getElementById('err').innerHTML = "Please enter valid data!";
                document.getElementById('err').style.color = 'red';
            }
        }

        $(document).ready(function() {
            $(document).on('click', '#Submit', function(e) {
                e.stopPropagation();
                if (contact()) {
                    let name = document.getElementById('Name').value;
                    let mobile = document.getElementById('Mobile').value;
                    let message = document.getElementById('Message').value;
                    let email = document.getElementById('email').value;

                    $.ajax({
                        url: "./queries.php",
                        method: "POST",
                        data: {
                            mobile: mobile,
                            name: name,
                            email: email,
                            message: message,
                            contact: true
                        },
                        success: function(response) {
                            if (response == 'success') {
                                toastr.success("Contact form submitted successfully!");
                                let name = document.getElementById('Name').value = "";
                                let mobile = document.getElementById('Mobile').value = "";
                                let email = document.getElementById('email').value = "";
                                let message = document.getElementById('Message').value = "";
                            } else {
                                toastr.error("Failed to update status.");
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            toastr.error(errorThrown);
                        }
                    });
                }

            });
        });

        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 2000,
            progressBar: true
        };
    </script>

</body>

</html>