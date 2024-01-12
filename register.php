<?php
session_start();
if (isset($_COOKIE["user"]) && isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    header('Location: /admin/dashboard.php');
} else if (isset($_COOKIE["user"]) && isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['role'] == 2) {
    header('Location: /manager/dashboard.php');
} else if (isset($_COOKIE["user"]) && isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['role'] == 0) {
    header("Location: $appUrl/user/dashboard.php");
}
include_once "config.php";
try {
    if (isset($_POST['btn_sub']) && $_POST['btn_sub'] == 'reg_user') {
        if (isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
            $username = ucwords($_POST['username']);
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone_no = $_POST['phone_no'] ? $_POST['phone_no'] : 'null';

            //Check user is exists or not
            $userExistQuery = "SELECT * FROM users where email='$email'";
            $result = mysqli_query($con, $userExistQuery);
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['success'] = false;
                $_SESSION['message'] = "Already registered with this email address.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $registerQuery = "INSERT INTO users (`username`,`email`,`password`,`phone_no`) VALUES ('$username','$email','$hashedPassword','$phone_no')";
                $reg_user = mysqli_query($con, $registerQuery);
                if ($reg_user) {
                    $_SESSION['success'] = true;
                    $_SESSION['message'] = "Registred successfully please login.";
                    echo "<script>
        setTimeout(function() {
           window.location.href = '$appUrl/login.php';
        },500);
    </script>";
                } else {
                    $_SESSION['success'] = false;
                    $_SESSION['message'] = mysqli_error($con);
                }
            }
        } else {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Please filled all required fields.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | <?php echo $_SESSION['site_name']; ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/adminStyle.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/toastr/toastr.min.js"></script>
    <style>
        .home {
            position: relative;
            width: fit-content;
            left: 94%;
            top: 0;
        }
    </style>
    <?php
    require_once "storeSetting.php"; ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <p class="text-nowrap logo-img text-center d-block w-100">
                            <img src="./uploads/settings/<?php echo $_SESSION['logo']; ?>" width="100" alt="">
                        </p>
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/index.php" title="Go to Home Page" class="text-black home p-1 d-flex justify-content-end"><i class="fa fa-home"></i></a>
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
                                <h2 class="text-center">Register</h2>
                                <form id="registrationForm" action="./register.php" method="post">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label><span class="text-danger">*</span>
                                        <input type="text" name="username" class="form-control" placeholder="Name" id="username" value="" autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label><span class="text-danger">*</span>
                                        <input type="text" name="email" class="form-control" placeholder="Email" id="email" value="" autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone_no" class="form-label">Phone no.</label>
                                        <input type="text" name="phone_no" class="form-control" placeholder="Phone No." id="phone_no" value="" autocomplete="off">
                                    </div>
                                    <label for="password" class="form-label">Password</label><span class="text-danger">*</span>
                                    <div class="input-group mb-3">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="" autocomplete="off">
                                        <span id="changeInputType" class="icon cursor-pointer input-group-text"><i id="eyeToggle" class="fa fa-eye-slash"></i></span>
                                    </div>
                                    <label for="c_password" class="form-label">Confirm Password</label><span class="text-danger">*</span>
                                    <div class="input-group mb-3">
                                        <input type="password" id="c_password" name="c_password" class="form-control" placeholder="Confirm Password" value="" autocomplete="off">
                                        <span id="changeConfirmInputType" class="icon cursor-pointer input-group-text"><i id="eyeToggle1" class="fa fa-eye-slash"></i></span>
                                    </div>
                                    <button name="btn_sub" value="reg_user" id="btn_sub" class="btn btn-outline-secondary w-100 py-8 fs-4 mb-2 rounded-2">Register</button>
                                    <div>
                                        <p class="fw-bold mt-1">Already registered?<a class="text-primary ms-2" href="login.php">Login Here.</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var registrationForm = document.getElementById("registrationForm");

            registrationForm.addEventListener("submit", function(event) {
                var usernameInput = document.getElementById("username");
                var emailInput = document.getElementById("email");
                var passwordInput = document.getElementById("password");
                var phoneInput = document.getElementById("phone_no");
                var confirmPasswordInput = document.getElementById("c_password");
                var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

                if (!usernameInput.value.trim() || !emailInput.value.trim() || !passwordInput.value.trim() || !confirmPasswordInput.value.trim()) {
                    toastr.error("Please fill in all required fields.");
                    event.preventDefault();
                } else if (!emailRegex.test(emailInput.value.trim())) {
                    toastr.error("Please enter valid email.");
                    event.preventDefault();
                } else if (phoneInput.value && isNaN(phoneInput.value.trim())) {
                    toastr.error("Please enter valid phone no.");
                    event.preventDefault();
                } else if (phoneInput.value && phoneInput.value.trim().length != 10) {
                    toastr.error("Please enter phone no. with 10 digits.");
                    event.preventDefault();
                } else if (passwordInput.value.trim() != confirmPasswordInput.value.trim()) {
                    toastr.error("Password and confirm password should be same.");
                    event.preventDefault();
                }
            });
        });

        $(document).ready(function() {
            $("#changeConfirmInputType").on("click", function() {
                var confirmPasswordInput = $("#c_password");

                if (confirmPasswordInput.attr("type") === "password") {
                    confirmPasswordInput.attr("type", "text");
                    $("#eyeToggle1").removeClass("fa-eye-slash").addClass("fa-eye");
                } else {
                    confirmPasswordInput.attr("type", "password");
                    $("#eyeToggle1").removeClass("fa-eye").addClass("fa-eye-slash");
                }
            });

        })

        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 2000,
            progressBar: true,
        };
    </script>

    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <script src="./assets/js/script.js"></script>
    <script src="./assets/font-awesome/all.min.js"></script>
</body>

</html>