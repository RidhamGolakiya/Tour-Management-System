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
$pageTitle = "Blogs";
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
        <h3>Blogs</h3>
        <a class="btn btn-outline-secondary" href="./create-blog.php">Add Blog</a>
      </div>
      </div><div class="row dashboard-widget-p-5">
        <div class="container-fluid" style="padding-top:0">
          <table id="dataTable" class="display table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable({
        "ajax": {
          "url": "../fetch.php?allBlogs=true",
          "dataSrc": ""
        },
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        aaSorting: [
          [3, 'asc']
        ],
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, 'All']
        ],
        "columns": [{
            "data": "blog_id"
          },
          {
            "data": "title"
          },
          {
            "render": function(data, type, full, meta) {
              const isChecked = full.status == 1;
              const toggleSwitchHTML = `
                        <div class="status_change">
                            <label class="form-check form-switch form-switch-sm cursor-pointer">
                                <input style="border:1px solid black"
                                    autocomplete="off"
                                    class="form-check-input cursor-pointer status_change"
                                    type="checkbox" value=${full.blog_id}
                                    ${isChecked ? 'checked' : ''}
                                >
                                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                        </div>
                    `;
              return toggleSwitchHTML;
            }
          },
          {
            "render": function(data, type, full, meta) {
              const formattedDate = moment(full.created_at).format("DD/MM/YYYY");
              const formattedTime = moment(full.created_at).format('LT');
              return `<span class="badge bg-light-info"><div class="mb-1">${formattedTime}</div><div>${formattedDate}</div></span>`;
            }
          },
          {
            // Action buttons column
            "render": function(data, type, full, meta) {
              return '<a href="edit-blog.php?blog_id=' + full.blog_id + '" name="edit" title="Edit Blog" value="Edit" class="btn btn-info edit_data"><span class="fa fa-pencil"></span></a>' +
                '<button value=' + full.blog_id + ' class="blogDelete btn btn-danger mx-1" title="Delete Blog" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
            },
            "orderable": false
          }
        ],
        "columnDefs": [{
          "targets": 0,
          "visible": false,
          "searchable": true
        }]
      });
    });

    $(document).on('click', '.blogDelete', function() {
      let val = $(this).val();
      Swal.fire({
        text: 'Are you sure want to delete this "Blog"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "../queries.php",
            method: "POST",
            data: {
              delete_blog: true,
              blog_id: val
            },
            success: function(response) {
              if (response == 1) {
                toastr.success("Blog deleted successfully");
                setTimeout(function() {
                  window.location.reload();
                }, 1000);
              } else {
                toastr.error("Error while deleting blog");
              }
            },
            error: function(xhr, textStatus, errorThrown) {
              toastr.error(errorThrown);
            }
          });
        }
      });
    });

    $(document).ready(function() {
      $(document).on('change', '.status_change', function(e) {
        e.stopPropagation();
        const blog_id = $(this).val();
        const status = this.checked ? 1 : 0;

        $.ajax({
          url: "../queries.php",
          method: "POST",
          data: {
            status_change: true,
            blog_id: blog_id
          },
          success: function(response) {
            if (response == 1) {
              toastr.success("Status updated successfully!");
            } else {
              toastr.error("Failed to update status.");
            }
          },
          error: function(xhr, textStatus, errorThrown) {
            toastr.error(errorThrown);
          }
        });
      });
    });

    toastr.options = {
      positionClass: "toast-top-right",
      timeOut: 2000,
      progressBar: true
    };
  </script>

  <script src="./manager.js"></script>
</div>
<?php
require_once("../components/footer.php");
?>