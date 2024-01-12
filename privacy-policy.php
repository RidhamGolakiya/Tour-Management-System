<?php
session_start();
include_once "config.php";
$selectPrivacyPolicy = "select privacy_policy,p_date from settings";
$result = mysqli_query($con,$selectPrivacyPolicy);
$row = mysqli_fetch_array($result);
$privacy_policy = $row['privacy_policy'];
$last_updated_date = $row['p_date'];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css">
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <title>Privacy Policy | <?php echo $_SESSION['site_name'] ?></title>
</head>
<body>
    <!-- Header -->
    <?php require_once "./components/navbarHome.php"; ?>

    <div class="container p-5">
    <p class="mt-3">Last Updated: <?php echo $last_updated_date; ?></p>
        <?php echo $privacy_policy; ?>
    </div>

    <!-- Footer -->
    <?php include_once "./components/footerHome.php"; ?>

    <!-- Scripts -->
    <script src="./assets/bootstrap/bootstrap.min.js"></script>
</body>
</html>
