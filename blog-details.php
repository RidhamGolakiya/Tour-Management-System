<?php
session_start();
$pageTitle = 'Blogs';
require_once "config.php";
if (isset($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];
    $blog = "SELECT blogs.*, users.username as name, users.image as img
    FROM blogs
    INNER JOIN users ON blogs.user_id = users.user_id
    WHERE blogs.blog_id = $blog_id;";
    $result = mysqli_query($con, $blog);
    if (!$result || mysqli_num_rows($result) == 0) {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Blog not found";
        header("location: /blog.php");
        exit();
    } else {
        $row = mysqli_fetch_array($result);
        $title = $row['title'];
        $category = $row['category'];
        $name = $row['name'];
        $user_image = $row['img'];
        $hero_image = $row['image'];
        $date = date('d M Y', strtotime($row['updated_at']));
        $description = $row['description'];
        $pageTitle = $title;
    }
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

</head>

<body>
    <?php
    require_once "./components/navbarHome.php";
    ?>

    <div style="background-image:url('./assets/images/old-fort.png');padding:8% 0;background-repeat:no-repeat;background-size:cover">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h5 class="h5"><a href="./blog.php" style="color: #9deeff;" class="text-decoration-none">Blog</a> / <span style="color: white;"><?php echo $category; ?></span></h5>
                    <h1 class="h1"><?php echo $title; ?></h1>
                    <div class="post d-flex text-white align-items-center">
                        <?php
                        if ($user_image != '') {
                            echo '<img src="./uploads/users/' . $user_image . '" class="avatar img-fluid" alt="' . $title . '">';
                        } else {
                            echo '<img class="img-fluid avatar" src="./assets/images/profile/user.jpg"  alt="User image">';
                        }
                        ?>
                        <div class="d-flex align-items-center"><i class="fa fa-calendar px-2"></i><?php echo $date; ?> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container section-padding">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <?php
                if ($hero_image != '') {
                    echo '<img src="./uploads/blogs/' . $hero_image . '" class="hero_img" alt="' . $title . '">';
                } else {
                    echo '<img class="img-fluid" src="./assets/images/tours/default.jpeg"  alt="Default image">';
                }
                ?>
                <h2 class="mt-5"><?php echo $title; ?></h2>
                <div><?php echo $description; ?></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
    include_once "./components/footerHome.php";
    ?>
    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <script src="./assets/font-awesome/all.min.js"></script>
</body>

</html>