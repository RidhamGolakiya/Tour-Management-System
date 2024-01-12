<?php
session_start();
$pageTitle = 'Packages';
require_once "config.php";
$tour_packages = "select * from tour_packages";
$result = mysqli_query($con, $tour_packages);
$fetchCountries = "SELECT * FROM countries";
$resultCountries = mysqli_query($con, $fetchCountries);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <title><?php echo $pageTitle . " | " . $_SESSION['site_name'] ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/header.css" />
    <script src="./assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <script src="./assets/toastr/toastr.min.js"></script>
    <script>
        let appUrl = <?php require_once "config.php"; $appUrl;?>//
        $(document).ready(function() {
            function load_data(query, selectedCountry, page) {
                $.ajax({
                    url: "fetch.php",
                    method: "POST",
                    data: {
                        packageName: query,
                        country: selectedCountry,
                        page: page,
                        search: true
                    },
                    dataType: 'json',
                    success: function(data) {
                        var packages = data.packages;
                        var totalRecords = data.totalRecords;
                        var totalPages = Math.ceil(totalRecords / 12);
                        var html = '';
                        if (packages.length > 0) {
                            $.each(packages, function(index, package) {
                                var stateName = package.state_name || '';
                                var formattedPrice = parseFloat(package.price).toFixed(2);

                                html += '<div class="col-md-4 main-div cursor-pointer">';
                                html += '<div class="sub-div">';
                                html += '<article id="post-' + package.tour_id + '" class="post-' + package.tour_id + '">';
                                html += '<div class="start_content">';
                                html += '<article class="card">';
                                html += '<div class="image-container">';
                                html += '<img class="img-fluid" src="./uploads/tours/' + package.images + '" alt="" decoding="async" loading="lazy">';
                                html += '</div>';
                                html += '</article>';
                                html += '<div>';
                                html += '<h3>' + package.tour_name + '</h3>';
                                html += '</div>';
                                html += '</div>';
                                html += '<p>';

                                if (stateName !== '') {
                                    html += '<span class="country_icon">';
                                    html += '<i class="fa fa-globe"></i> ';
                                    html += package.country_name;
                                    html += '</span>';
                                    html += '<span class="country_icon">';
                                    html += '<i class="fas fa-map-marker-alt"></i> ';
                                    html += stateName;
                                    html += '</span>';
                                } else {
                                    html += '<span class="country_icon">';
                                    html += '<i class="fa fa-globe"></i> ';
                                    html += package.country_name;
                                    html += '</span></p>';
                                }
                                html += '<p>';
                                html += `<a href="./package-details.php?packageId=${package.tour_id}" class="default-btn">`;
                                html += '<span>Book Now | </span>';
                                html += '<span>₹ ' + formattedPrice + '</span>';
                                html += '</a>';
                                html += '</p>';
                                html += '</article>';
                                html += '</div>';
                                html += '</div>';
                            });
                            // Modify the pagination HTML
                            html += '<nav aria-label="Page navigation"><ul class="pagination justify-content-center pagination-rounded">';
                            for (var i = 1; i <= totalPages; i++) {
                                html += '<li class="page-item mx-1 ' + (page == i ? 'page-active' : '') + '"><a class="pagination-link" href="javascript:void(0);" data-page="' + i + '">' + i + '</a></li>';
                            }
                            html += '</ul></nav>';
                        } else {
                            html = '<p class="text-center">We couldn\'t find any results matching your search terms.</p>';
                        }
                        $('#result').html(html);
                    }
                });
            }

            // Initial load and updates based on user input
            $('#searchPackage, #countrySelect').on('change keyup', function() {
                var search = $('#searchPackage').val();
                var selectedCountry = $('#countrySelect').val();
                load_data(search, selectedCountry);
            });

            $('#reset').on('click', function() {
                $('#searchPackage').val('');
                $('#countrySelect').val('all');
                load_data('', 'all', 1);
            });

            // Load data on page load
            load_data('', 'all', 1);

            $(document).on('click', '.pagination a.pagination-link', function() {
                var page = $(this).data('page');
                var search = $('#searchPackage').val();
                var selectedCountry = $('#countrySelect').val();
                load_data(search, selectedCountry, page); // Load the clicked page
            });
        });
    </script>
    <?php
    include_once "./storeSetting.php";
    ?>
</head>

<body>
    <?php
    include_once "./components/navbarHome.php";
    ?>

    <div class="contact-content" style="background-image: url('./assets//images/web-images/contact.jpg');background-repeat:no-repeat;background-size:cover">
        <div class="text-center p-5" style="background-color: rgb(32, 10, 10); opacity: 0.55; ">
            <h1 style="font-family: unset; color:white;">Packages</h1>
            <h3 style="font-family: 'Times New Roman', Times, serif; color:white;" class="pt-2"><a href="/" style="color:white; text-decoration: none;">Home</a>&nbsp; >> &nbsp;Packages</h3>
        </div>
    </div>
    <?php
    if (isset($_SESSION['message']) && isset($_SESSION['success'])) {
        $message = $_SESSION['message'];
        $success = $_SESSION['success'];
        $toastType = $success ? 'success' : 'error';
        echo "<script>
          toastr.options = {
            positionClass: 'toast-top-right',
            timeOut: 5000,
            progressBar: true,
          }; toastr.$toastType('$message');</script>";
        unset($_SESSION['message']);
        unset($_SESSION['success']);
    }
    ?>
    <div id="content" class="site-content">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <div class="container">
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="searchPackage" placeholder="Search for a Package">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <select id="countrySelect" class="form-select">
                                    <option value="all">All Countries</option>
                                    <?php
                                    while ($rowCountry = mysqli_fetch_assoc($resultCountries)) {
                                        echo '<option value="' . $rowCountry['id'] . '">' . $rowCountry['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <button class="reset-button" id="reset">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="row destination-archive mt-4" id="result">
                        <!-- Tour package cards will be displayed here -->
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <ul class="pagination" id="pagination">
                                <!-- Pagination links will be generated here -->
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Footer -->
    <?php
    include_once "./components/footerHome.php";
    ?>

    <script src="./assets/js/app.min.js"></script>
    <script src="./assets/font-awesome/all.min.js"></script>
    <script src="./assets/js/moment.js"></script>
    <script src="./assets/js/script.js"></script>
</body>

</html>