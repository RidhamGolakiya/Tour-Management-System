<?php
session_start();
require_once "../config.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
}
// Check user has admin role or not
else if (isset($_SESSION["role"]) && $_SESSION["role"] != 1) {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the admin site.";
    header("location: $appUrl/login.php");
    exit;
}
$pageTitle = "Settings";
require_once "../components/header.php";

// Fetch settings from database
try {
    $settings = "SELECT * FROM settings";
    $result = mysqli_query($con, $settings);
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
        $logo = $row['logo'];
        $favicon = $row['favicon'];
        $site_name = $row['site_name'];
        $themeColor = $row['themeColor'];
        $privacy_policy = $row['privacy_policy'];
        $terms_condition = $row['terms_condition'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (isset($_POST['btnSetting'])) {
    $updatedSitename = $_POST['site_name'];
    $updatedTheme = $_POST['themeColor'];

    // Process Logo Image
    $logoName = $logo; // Set default logo name
    if ($_FILES["logo"]["tmp_name"]) {
        $targetDirectory = "../uploads/settings/";
        $newFileName = uniqid() . "_" . basename($_FILES["logo"]["name"]);
        $targetFile = $targetDirectory . $newFileName;

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header('Location: /admin/dashboard.php');
            exit;
        }

        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
            $logoName = $newFileName;
            if (file_exists("../uploads/settings/" . $logo)) {
                unlink("../uploads/settings/" . $logo);
            }
        } else {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Error uploading logo.";
            header('Location: /admin/dashboard.php');
            exit;
        }
    }

    // Process Favicon Image
    $faviconName = $favicon; // Set default favicon name
    if ($_FILES["favicon"]["tmp_name"]) {
        $targetDirectory = "../uploads/settings/";
        $newFileName = uniqid() . "_" . basename($_FILES["favicon"]["name"]);
        $targetFile = $targetDirectory . $newFileName;

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header('Location: /admin/dashboard.php');
            exit;
        }

        if (move_uploaded_file($_FILES["favicon"]["tmp_name"], $targetFile)) {
            $faviconName = $newFileName;
            if (file_exists("../uploads/settings/" . $favicon)) {
                unlink("../uploads/settings/" . $favicon);
            }
        } else {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Error uploading favicon.";
            header('Location: /admin/dashboard.php');
            exit;
        }
    }

    $updatedSetting = "UPDATE settings SET site_name='$updatedSitename', logo='$logoName', favicon='$faviconName',themeColor='$updatedTheme'";

    $updateSetting = mysqli_query($con, $updatedSetting);

    if ($updateSetting) {
        $_SESSION['success'] = true;
        $_SESSION['message'] = "Settings has been updated successfully";
        $_SESSION['site_name'] = $updatedSitename;
        $_SESSION['logo'] = $logoName;
        $_SESSION['favicon'] = $faviconName;
        $_SESSION['themeColor'] = $updatedTheme;

        echo "<script>let settings = localStorage.getItem('settings')
        let isSuccess = '" . $_SESSION['success'] . "';
        let site_name = '" . $_SESSION['site_name'] . "';
        let logo = '" . $_SESSION['logo'] . "';
        let favicon = '" . $_SESSION['favicon'] . "';
        let themeColor = '" . $_SESSION['themeColor'] . "';
        if (isSuccess) {
            settings = settings && JSON.parse(settings);
            localStorage.setItem('settings', JSON.stringify({
                ...settings,
                site_name: site_name,
                logo: logo,
                favicon:favicon,
                themeColor:themeColor
            }));
            setTimeout(function() {
                window.location.href = '" . ("/admin/dashboard.php") . "';
            }, 100);
        }</script>";
        exit;
    } else {
        $_SESSION['success'] = false;
        $_SESSION['message'] = mysqli_error($con);
    }
}
?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="/" class="navbar-brand" style="font-size:30px">
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
                <div class="">
                    <h4>Settings</h4>
                </div>

                <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting" type="button" role="tab" aria-controls="home" aria-selected="true">Settings</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab" aria-controls="profile" aria-selected="false">Terms & Conditions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-controls="contact" aria-selected="false">Privacy Policy</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-4">
                                                <div class="col-12"><label class="form-label mb-4">Change Favicon:</label>
                                                    <div class="d-block">
                                                        <div class="image-picker">
                                                            <div class="image previewImage imagePreviewUrl"><img id="previewImage" src="<?php echo $favicon ? "../uploads/settings/" . $favicon : "/assets/images/profile/user.jpg"; ?>" alt="img" width="75" height="100" class="image image-circle image-mini h-100"><span class="picker-edit rounded-circle text-gray-500 fs-small cursor-pointer">
                                                                    <input class="upload-file" name="favicon" id="imageInput" title="[object Object]" type="file" accept=".png, .jpg, .jpeg">
                                                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil" class="svg-inline--fa fa-pencil " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                                        <path fill="currentColor" d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                                                    </svg></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-4">
                                                <div class="col-12"><label class="form-label mb-4">Change Logo:</label>
                                                    <div class="d-block">
                                                        <div class="image-picker">
                                                            <div class="image previewImage imagePreviewUrl"><img id="previewImage1" src="<?php echo $logo ? "../uploads/settings/" . $logo : "/assets/images/profile/user.jpg"; ?>" alt="img" width="75" height="100" class="image image-circle image-mini h-100"><span class="picker-edit rounded-circle text-gray-500 fs-small cursor-pointer">
                                                                    <input class="upload-file" name="logo" id="imageInput1" title="[object Object]" type="file" accept=".png, .jpg, .jpeg">
                                                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil" class="svg-inline--fa fa-pencil " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                                        <path fill="currentColor" d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                                                    </svg></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label class="mb-2"><b>Site Name: </b></label>
                                            <input type="text" name="site_name" class="form-control" value="<?php echo $site_name; ?>" placeholder="Site Name">
                                        </div>
                                        <div class="col-4">
                                            <label class="mb-2"><b>Theme Color: </b></label>
                                            <input type="color" name="themeColor" class="form-control" value="<?php echo $themeColor; ?>">
                                        </div>
                                        <div class="d-flex mt-5">
                                            <div><button class="btn btn-outline-secondary me-3" name="btnSetting">Save</button></div><a class="btn btn-secondary me-3" href="/admin/dashboard.php">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="terms" role="tabpanel" aria-labelledby="terms-tab">
                        <form action="../queries.php" id="terms_condition_form" method="post">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="col-md-12 mb-3">
                                        <div id="editor-container" style="height: 300px;"></div>
                                        <input type="hidden" name="terms_condition" id="terms_condition">
                                    </div>
                                    <div class="d-flex mt-5">
                                        <div><button class="btn btn-outline-secondary me-3" name="btn_terms_condition">Save</button></div><a class="btn btn-secondary me-3" href="/admin/dashboard.php">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                        <form action="../queries.php" id="privacy_policy_form" method="post">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="col-md-12 mb-3">
                                        <div id="editor-container1" style="height: 300px;"></div>
                                        <input type="hidden" name="privacy_policy" id="privacy_policy">
                                    </div>
                                    <div class="d-flex mt-5">
                                        <div><button class="btn btn-outline-secondary me-3" name="btn_privacy_policy">Save</button></div><a class="btn btn-secondary me-3" href="/admin/dashboard.php">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        }]
    ];

    // Handle the terms and conditions form
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        }
    });

    var termsConditionForm = document.getElementById('terms_condition_form');
    var termsConditionInput = document.getElementById('terms_condition');

    var existingTermsCondition = <?php echo json_encode($terms_condition); ?>;
    quill.root.innerHTML = existingTermsCondition;

    termsConditionForm.addEventListener('submit', function(event) {
        var termsCondition = quill.root.innerHTML;
        termsConditionInput.value = termsCondition;
    });


    // Handle the privacy policy form
    var quill1 = new Quill('#editor-container1', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        }
    });

    var privacyPolicyForm = document.getElementById('privacy_policy_form');
    var privacyPolicyInput = document.getElementById('privacy_policy');

    var existingPrivacyPolicy = <?php echo json_encode($privacy_policy); ?>;
    quill1.root.innerHTML = existingPrivacyPolicy;

    privacyPolicyForm.addEventListener('submit', function(event) {
        var privacyPolicy = quill1.root.innerHTML;
        privacyPolicyInput.value = privacyPolicy;
    });



    $(document).ready(function() {
        $("#imageInput").on('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    $("#previewImage").attr('src', reader.result);
                };
                reader.readAsDataURL(file);
            }
        });

        $("#imageInput1").on('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    $("#previewImage1").attr('src', reader.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="./admin.js"></script>
<?php
require_once "../components/footer.php";
?>