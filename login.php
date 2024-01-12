<?php
session_start();
require_once 'vendor/autoload.php';
include_once "config.php";

function redirectIfLoggedIn()
{
  if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] == 1) {
      header('Location: /admin/dashboard.php');
    } elseif ($_SESSION['role'] == 2) {
      header('Location: /manager/dashboard.php');
    } elseif ($_SESSION['role'] == 0) {
      header('Location: /user/dashboard.php');
    }
    exit;
  }
}

function handleGoogleLogin($con, $client, $service)
{
  if (isset($_GET['google_login'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
  } else if (isset($_GET['code'])) {
    $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $token = $client->getAccessToken();
    $client->setAccessToken($token);

    // Get user info
    $user = $service->userinfo->get();
    $googleEmail = $user->email;
    $googlePicture = $user->picture;
    $googleFirstName = $user->givenName;
    $googleLastName = $user->familyName;
    $fullName = trim($googleFirstName) . ' ' . trim($googleLastName);
    $query = "SELECT * FROM users WHERE email='$googleEmail'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 0) {
      $insertQuery = "INSERT INTO users (email, username,image, role) VALUES ('$googleEmail', '$fullName','$googlePicture', 0)";
      $result = mysqli_query($con, $insertQuery);
      if ($result) {
        $query = "SELECT user_id,email FROM users WHERE email='$googleEmail'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result);
        if ($row) {
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['email'] = $row['email'];
          $_SESSION['user'] = $user;
          $_SESSION['role'] = 0;
          echo "<script>
          localStorage.setItem('user', JSON.stringify(" . json_encode($user) . "));
          setTimeout(function() {
             window.location.href = '/user/dashboard.php';
          },100);
      </script>";
          exit;
        }
      }
    } else {
      $row = mysqli_fetch_array($result);
      $_SESSION['user'] = $user;
      $_SESSION['name'] = $row['username'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['user_id'] = $row['user_id'];
      if ($row['role'] == 1) {
        $path = "/admin/dashboard.php";
      } elseif ($row['role'] == 2) {
        $path = "/manager/dashboard.php";
      } elseif ($row['squestion'] == null || $row['squestion'] == '') {
        $_SESSION['create_question'] = true;
        $path = "/security-question.php";
      } else {
        $path = "/user/dashboard.php";
      }
      echo "<script>
      localStorage.setItem('user', JSON.stringify(" . json_encode($row) . "));
      setTimeout(function() {
         window.location.href = '" . $path . "';
      },100);
  </script>";
      exit;
    }
  }
}

function handleRegularLogin($con)
{
  if (isset($_POST['login_btn']) && $_POST['login_btn'] == 'log_btn') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $query = "SELECT * FROM users where email='$email'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_array($result);
      $comparePassword = $row && password_verify($password, $row['password']);
      if ($comparePassword == true) {
        $imageName = $row['image'] ? $row['image'] : '';
        $_SESSION['image'] = $imageName ? $imageName : '';
        $_SESSION['name'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user'] = $row;
        $_SESSION['email'] = $row['email'];
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['success'] = true;
        $_SESSION['message'] = "Logged in successfully";
        if ($row['role'] == 1) {
          $path = "/admin/dashboard.php";
        } elseif ($row['role'] == 2) {
          $path = "/manager/dashboard.php";
        } elseif ($row['squestion'] == null || $row['squestion'] == '') {
          $_SESSION['create_question'] = true;
          $path = "/security-question.php";
        } else {
          $path = "/user/dashboard.php";
        }

        $userData = [
          'name' => $row['username'],
          'role' => $row['role'],
          'image' => $imageName ? $imageName : '',
          'email' => $row['email'],
          'user_id' => $row['user_id'],
        ];

        // Encode the user data as JSON
        $userDataJSON = json_encode($userData);

        // Use JavaScript to set the user data in localStorage
        echo "<script>
            localStorage.setItem('user', '" . $userDataJSON . "');
            setTimeout(function() {
               window.location.href = '" . $path . "';
            }, 100);
        </script>";


        exit;
      } else {
        $_SESSION['message'] = "Credentials not matched with our database.";
        $_SESSION['success'] = false;
      }
    } else {
      $_SESSION['message'] = "Credentials not matched with our database.";
      $_SESSION['success'] = false;
    }
  }
}



// Access environment variables
if (!isset($_ENV['GOOGLE_CLIENT_ID']) || !isset($_ENV['GOOGLE_SECRET']) || !isset($_ENV['GOOGLE_REDIRECT_URI'])) {
  $_SESSION['success'] = false;
  $_SESSION['message'] = 'GOOGLE_CLIENT_ID,GOOGLE_SECRET,GOOGLE_REDIRECT_URI one or more required variable is not defined in .env';
} else {
  try {
    $GOOGLE_CLIENT_ID = $_ENV['GOOGLE_CLIENT_ID'];
    $GOOGLE_SECRET = $_ENV['GOOGLE_SECRET'];
    $GOOGLE_REDIRECT_URI = $_ENV['GOOGLE_REDIRECT_URI'];

    // Create a new Google Client
    $client = new Google_Client();
    $client->setClientId($GOOGLE_CLIENT_ID);
    $client->setClientSecret($GOOGLE_SECRET);
    $client->setRedirectUri($GOOGLE_REDIRECT_URI);
    $client->addScope('email');
    $client->addScope("profile");

    // Create an instance of Google_Service_Oauth2
    $service = new Google_Service_Oauth2($client);

    redirectIfLoggedIn();
    handleGoogleLogin($con, $client, $service);
    handleRegularLogin($con);
  } catch (Exception $e) {
    header('Location: /login.php');
    exit;
  }
}


?>

<!doctype html>
<html lang="en">

<head>
  <!-- Meta information -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | <?php echo $_SESSION['site_name']; ?></title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
  <link rel="stylesheet" href="./assets/css/adminStyle.css">
  <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
  <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">

  <!-- JavaScript libraries -->
  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/toastr/toastr.min.js"></script>

  <!-- Inline CSS style -->
  <style>
    .home {
      position: relative;
      width: fit-content;
      left: 94%;
      top: 0;
    }

    /* Style the Google login button */
    .google-login {
      text-align: center;
    }

    .btn-google {
      background-color: #fff;
      color: black !important;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      display: inline-block;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s;
    }

    .btn-google:hover {
      background-color: black !important;
      color: #fff !important;
    }

    /* Style the Google icon */
    .google-icon {
      display: inline-block;
      vertical-align: middle;
      margin-right: 10px;
    }

    .google-icon-image {
      width: 24px;
      height: 24px;
      vertical-align: middle;
    }
  </style>

  <?php
  require_once "storeSetting.php";
  ?>
</head>

<body>
  <!-- Page wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <p class="text-nowrap logo-img text-center d-block w-100">
            <img src="./uploads/settings/<?php echo $_SESSION['logo']; ?>" width="100" alt="">
          </p>
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="/index.php" title="Go to Home Page" class="text-black home p-1 d-flex justify-content-end"><i class="fa fa-home"></i></a>

                <!-- Display login messages -->
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

                <h2 class="text-center">Login</h2>
                <form action="./login.php" method="post" id="loginForm">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label><span class="text-danger">*</span>
                    <input type="text" name="email" class="form-control" placeholder="Email" id="email">
                  </div>
                  <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                    <a href="./forgot-password.php" class="text-primary ms-2">Forgot Password?</a>
                  </div>
                  <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="">
                    <span id="changeInputType" class="icon cursor-pointer input-group-text"><i id="eyeToggle" class="fa fa-eye-slash"></i></span>
                  </div>
                  <button name="login_btn" value="log_btn" id="login_btn" class="btn btn-outline-secondary w-100 py-8 fs-4 mb-2 rounded-2">Sign In</button>
                  <div>
                    <p class="fw-bold mt-1">Not registered yet?<a class="text-primary ms-2" href="register.php">Register Here.</a></p>
                  </div>
                </form>
                <div class="google-login">
                  <p class="text-center fw-bolder">Or</p>
                  <a href="login.php?google_login=true" class="btn btn-google">
                    <span class="google-icon">
                      <img src="./assets/images/google-icon.png" alt="Google Icon" class="google-icon-image">
                    </span>
                    Login with Google
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- JavaScript and libraries -->
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            var registrationForm = document.getElementById("loginForm");

            registrationForm.addEventListener("submit", function(event) {
              var emailInput = document.getElementById("email");
              var passwordInput = document.getElementById("password");
              var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

              if (!emailInput.value.trim() || !passwordInput.value.trim()) {
                toastr.error("Please fill in all required fields.");
                event.preventDefault();
              } else if (!emailRegex.test(emailInput.value.trim())) {
                toastr.error("Please enter valid email.");
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