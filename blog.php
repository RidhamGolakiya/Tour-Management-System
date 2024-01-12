<?php
session_start();
$pageTitle = 'Blogs';
require_once "config.php";
$blogs = "select * from blogs where status = 1 order by updated_at desc";
$result = $con->query($blogs);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <title><?php echo $pageTitle . " | " . $_SESSION['site_name'] ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
    <script src="./assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <script src="./assets/toastr/toastr.min.js"></script>
    <?php
    include_once "./storeSetting.php";
    ?>
</head>

<body>
    <?php
    require_once "./components/navbarHome.php";
    ?>

    <div class="contact-content" style="background-image: url('./assets//images/web-images/contact.jpg');background-repeat:no-repeat;background-size:cover">
        <div class="text-center p-5" style="background-color: rgb(32, 10, 10); opacity: 0.55; ">
            <h1 style="font-family: unset; color:white;">Blogs</h1>
            <h3 style="font-family: 'Times New Roman', Times, serif; color:white;" class="pt-2"><a href="/" style="color:white; text-decoration: none;">Home</a>&nbsp; >> &nbsp;Blogs</h3>
        </div>
    </div>
    <div>
        <div>
            <main>
                <div class="container">
                    <div class="row mt-5">
                        <?php
                        if (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == false) {
                            $message = $_SESSION['message'];
                            echo "<p class='alert alert-danger'> $message </p>";
                            unset($_SESSION['message']);
                            unset($_SESSION['success']);
                        } else if (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == true) {
                            $message = $_SESSION['message'];
                            echo "<p class='alert alert-success'> $message </p>";
                            unset($_SESSION['message']);
                            unset($_SESSION['success']);
                        }
                        ?>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            foreach ($result as $blog) {
                                $blog_id = $blog['blog_id'];
                                $title = $blog['title'];
                                $image = $blog['image'];
                                $category = $blog['category'];
                                $dateMonth = date('M', strtotime($blog['updated_at']));
                                $date = date('d', strtotime($blog['updated_at']));
                                echo '<div class="col-md-4 col-12">';
                                echo     '<a href="blog-details.php?blog_id=' . $blog_id . '"><div class="main_div">';
                                echo '<div>';
                                if ($image != '') {
                                    echo '<img class="img-fluid" src="./uploads/blogs/' . $image . '"  alt="' . $title . '">';
                                } else {
                                    echo '<img class="img-fluid" src="./assets/images/tours/default.jpeg"  alt="Default image">';
                                }
                                echo ' <div class="date">';
                                echo '<p><span>' . $dateMonth . '</span><i>' . $date . '</i> </p></div></div>';
                                echo '<div class="con"> <span class="category">' . $category . '</span>';
                                echo '<h5>' . $title . '</h5> </div></div></a></div>';
                            }
                        } else {
                            echo '<div class="col-12 text-center"><h5>No blogs are available at the moment. Please check back later for exciting travel updates!</h5></div>';
                        }
                        ?>
                    </div>
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