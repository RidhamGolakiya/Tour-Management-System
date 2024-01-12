<?php
session_start();
require_once "../config.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header('location: /login.php');
}
// Check user has manager role or not
else if (isset($_SESSION["role"]) && $_SESSION["role"] != 2) {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the manager site.";
    header('Location: /login.php');
    exit;
}
if (isset($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];

    $sql = "SELECT * FROM blogs WHERE blog_id = $blog_id";
    $result = mysqli_query($con, $sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $category = $row['category'];
        $description = $row['description'];
        $image = $row['image'];
    } else {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Blog not found.";
    }
} else {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Blog id not found.";
    header('Location: /admin/blogs.php');
}

$pageTitle = "Edit Blog";
require_once "../components/header.php";
?>

<!--  Body Wrapper -->
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

        <div class="container-fluid">
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
                <h3>Edit Tour Package</h3>
                <a class="btn btn-outline-secondary" href="./blogs.php">Back</a>
            </div>
        </div>

        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">
                    <form class="" action="../queries.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" value="<?php echo $blog_id; ?>" name="blog_id">
                            <input type="hidden" value="<?php echo $image; ?>" name="old_image">
                            <div class="row">
                                <div class="col-md-12 mb-3 d-flex justify-content-start align-items-center">
                                    <label for="images">Select Image:</label><span class="text-danger">*</span>
                                    <ul class="list-style-none mt-3 mx-2">
                                        <li class="uploadBtn add">
                                            <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg">
                                        </li>
                                    </ul>
                                    <div id="imagePreview"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title:</label><span class="text-danger">*</span>
                                    <input type="text" value="<?php echo $title; ?>" name="title" id="title" placeholder="Title" class="form-control">
                                    <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category:</label><span class="text-danger">*</span>
                                    <input type="text" value="<?php echo $category; ?>" name="category" placeholder="Category" class="form-control">
                                    <span class="text-danger d-block fw-400 fs-small mt-2"></span>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description:</label><span class="text-danger">*</span>
                                    <div id="editor-container" style="height: 300px;"></div>
                                    <input type="hidden" name="description" id="description">

                                </div>
                                <div class="d-flex mt-5 justify-content-end">
                                    <button class="btn btn-outline-secondary me-3" name="updt_blog">Save</button>
                                    <a class="btn btn-secondary" href="./blogs.php">Cancel</a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewContainer = document.getElementById('imagePreview');
        const existingImagesString = "<?php echo $image; ?>";
        const image = document.createElement('img');
        image.src = "../uploads/blogs/" + existingImagesString;
        image.classList.add('preview-image');
        previewContainer.appendChild(image);

        document.getElementById('imageInput').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';

            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const image = document.createElement('img');
                image.src = e.target.result;
                image.classList.add('preview-image');
                previewContainer.appendChild(image);
            };

            reader.readAsDataURL(file);
        });
    });

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

    var existingOtherDetails = <?php echo json_encode($description); ?>;
    quill.root.innerHTML = existingOtherDetails

    document.querySelector('form').addEventListener('submit', function(event) {
        var otherDetailsContent = quill.root.innerHTML;
        document.getElementById('description').value = otherDetailsContent;
    });

    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector("form");

        form.addEventListener("submit", function(event) {
            const existingImagesString = "<?php echo $image; ?>";
            var imageInput = document.getElementById("imageInput");
            var titleInput = document.querySelector("input[name='title']");
            var categoryInput = document.querySelector("input[name='category']");
            var descriptionInput = document.querySelector("input[name='description']");

            if (!existingImagesString && !imageInput.files.length) {
                toastr.error("Please select an image.");
                event.preventDefault();
                return;
            }

            // Check other fields for empty values
            if (
                titleInput.value.trim() === "" ||
                categoryInput.value.trim() === "" ||
                descriptionInput.value.trim() === ""
            ) {
                toastr.error("Please ensure that all fields are filled out.");
                event.preventDefault();
                return;
            }
        });
    });
</script>

<script src="./manager.js"></script>
<?php
require_once("../components/footer.php");
?>