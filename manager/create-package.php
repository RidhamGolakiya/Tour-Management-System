<?php
session_start();
include_once '../config.php';
if (!isset($_SESSION['user'])) {
    header("location: $appUrl/login.php");
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != 2) {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the manager site.";
    header("location: $appUrl/login.php");
    exit;
}
$pageTitle = "Tour Packages";
require_once "../components/header.php";
?>

<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                        <a href="<?php echo $appUrl;?>" class="navbar-brand" style="font-size:30px">
                    <div class="d-flex align-items-center"><img src="../uploads/settings/<?php echo $_SESSION['logo'] ?>" class="img-fluid" alt="logo" width="50" height="50"><span class="mx-2 my-1" style="font-size:20px"><?php echo $_SESSION['site_name'] ?></span></div>
                </a>
                <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <div class="sidebar">
                    <ul id="sideNav">
                    </ul>
                </div>
            </nav>
        </div>
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
        <!--  Header Start -->
        <?php require_once "../components/profileHeader.php" ?>
        <!--  Header End -->

        <div>
            <div class="p-5">
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
                <div class="d-flex justify-content-between">
                    <h3>Create Tour Package</h3>
                    <a class="btn btn-outline-secondary" href="./tour-packages.php">Back</a>
                </div>
                <div class="container-fluid p-0">
                    <div class="card">
                        <div class="card-body">
                            <form class="" action="../queries.php" enctype="multipart/form-data" method="post">
                                <div class="row">
                                    <div class="col-md-12 mb-3 d-flex justify-content-start align-items-center">
                                        <label for="images">Select Image:</label><span class="text-danger">*</span>
                                        <ul class="list-style-none mt-3 mx-2">
                                            <li class="uploadBtn add">
                                                <input type="file" name="image" id="imageInput" accept="image/jpeg, image/png,image/jpg">
                                            </li>
                                        </ul>
                                        <div id="imagePreview"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Package Name: </label><span class="text-danger">*</span>
                                        <input type="text" name="tour_name" placeholder="Package Name" class="form-control" value="">
                                        <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Description: </label><span class="text-danger">*</span>
                                        <input type="text" name="description" class="form-control" placeholder="Description" value="">
                                        <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Country Name:</label><span class="text-danger">*</span>
                                        <select id="countrySelect" name="country_name" class="form-select">
                                            <option value="">Select Country</option>
                                        </select>
                                        <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">State Name:</label><span class="text-danger">*</span>
                                        <select id="stateSelect" name="state_name" class="form-select">
                                            <option value="">Select State</option>
                                        </select>
                                        <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price: </label><span class="text-danger">*</span>
                                        <input type="number" name="price" class="form-control" placeholder="Price" value="">
                                        <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Other Details:</label><span class="text-danger">*</span>
                                        <div id="editor-container" style="height: 300px;"></div>
                                        <input type="hidden" name="other_details" id="other_details_input">

                                    </div>
                                    <div class="d-flex mt-5 justify-content-end">
                                        <button class="btn btn-outline-secondary me-3" name="pack_save">Save</button>
                                        <a class="btn btn-secondary" href="./tour-packages.php">Cancel</a>
                                    </div>
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
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],

        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }],

        [{
            'size': ['small', false, 'large', 'huge']
        }],
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }],

        [{
            'color': []
        }, {
            'background': []
        }],
        [{
            'align': []
        }],
    ];
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        }
    });

    document.querySelector('form').addEventListener('submit', function(event) {
        var otherDetailsContent = quill.root.innerHTML;

        document.getElementById('other_details_input').value = otherDetailsContent;
    });
    const previewContainer = document.getElementById('imagePreview');
    $("#imageInput").on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const image = document.createElement('img');
                image.src = e.target.result;
                image.classList.add('preview-image');
                previewContainer.appendChild(image);
            };

            reader.readAsDataURL(file);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector("form");

        form.addEventListener("submit", function(event) {
            var imageInput = document.getElementById("imageInput");
            var tourNameInput = document.querySelector("input[name='tour_name']");
            var descriptionInput = document.querySelector("input[name='description']");
            var countryNameInput = document.querySelector("input[name='country_name']");
            var stateNameInput = document.querySelector("input[name='state_name']");
            var priceInput = document.querySelector("input[name='price']");
            var otherDetailsInput = document.getElementById("other_details_input");

            if (!imageInput.files.length) {
                toastr.error("Please select an image.");
                event.preventDefault();
                return;
            }

            // Check other fields for empty values
            if (
                tourNameInput.value.trim() === "" ||
                descriptionInput.value.trim() === "" ||
                countryNameInput.value.trim() === "" ||
                stateNameInput.value.trim() === "" ||
                priceInput.value.trim() === "" ||
                otherDetailsInput.value.trim() === ""
            ) {
                toastr.error("Please ensure that all fields are filled out.");
                event.preventDefault();
                return;
            }

            var priceValue = parseInt(priceInput.value, 10);

            if (isNaN(priceValue) || priceValue !== Math.floor(priceValue)) {
                toastr.error("Price must be an integer.");
                event.preventDefault();
                return;
            }
        });
    });

    $(document).ready(function () {
        $.ajax({
            url: '../fetch.php?allcountries=true',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var countrySelect = $('#countrySelect');
                countrySelect.empty().append('<option value="">Select Country</option>');
                $.each(data, function (key, value) {
                    countrySelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });

        $('#countrySelect').change(function () {
            var selectedCountry = $(this).val();
            var stateSelect = $('#stateSelect');
            $.ajax({
                url: '../fetch.php',
                method: 'GET',
                dataType: 'json',
                data: { country_id: selectedCountry },
                success: function (data) {
                    stateSelect.empty().append('<option value="">Select State</option>');
                    $.each(data, function (key, value) {
                        stateSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });
    });
</script>

<script src="./manager.js"></script>
<?php
require_once("../components/footer.php");
?>