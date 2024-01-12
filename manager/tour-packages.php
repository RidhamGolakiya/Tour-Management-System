<?php
session_start();
require_once "../config.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
  $_SESSION['success'] = false;
  $_SESSION['message'] = "Authentication failed";
  header("location: $appUrl/login.php");
}
// Check user has manager role or not
else if (isset($_SESSION["role"]) && $_SESSION["role"] != 2) {
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
        <h3>Tour Packages</h3>
        <a class="btn btn-outline-secondary" href="./create-package.php">Create Tour Package</a>
      </div>
      </div><div class="row dashboard-widget-p-5">
        <div class="container-fluid" style="padding-top:0">
          <table id="dataTable" class="display table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Tour name</th>
                <th>Description</th>
                <th>Price</th>
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
          "url": "../fetch.php?allPackage=true",
          "dataSrc": ""
        },
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        aaSorting: [
          [4, 'asc']
        ],
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, 'All']
        ],
        "columns": [{
            "data": "tour_id"
          },
          {
            "data": "tour_name"
          },
          {
            "data": "description"
          },
          {
            "render": function(data, type, full, meta) {
              const formattedPrice = new Intl.NumberFormat('en-IN').format(full.price);
              return `<span>₹ ${formattedPrice}</span>`;
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
              return '<a href="tour-package-details.php?id=' + full.tour_id + '" name="view" title="View Package" value="view" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></a>' +
                '<a href="edit-package.php?id=' + full.tour_id + '" name="edit" title="Edit Package" value="Edit" class="btn btn-info edit_data"><span class="fa fa-pencil"></span></a>' +
                '<button value=' + full.tour_id + ' class="packageDelete btn btn-danger mx-1" title="Delete Package" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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

    $(document).on('click', '.packageDelete', function() {
      let val = $(this).val();
      Swal.fire({
        text: 'Are you sure want to delete this "Package"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: `../queries.php?tour_id=${val}`,
            method: "GET",
            success: function(response) {
              if (response == 1) {
                toastr.success("Tour package deleted successfully");
                setTimeout(function() {
                  window.location.reload();
                }, 1000);
              } else {
                toastr.error(response);
              }
            },
            error: function(xhr, textStatus, errorThrown) {
              toastr.error(errorThrown);
            }
          });
        }
      });
    });
  </script>

  <script src="./manager.js"></script>
</div>
<?php
require_once("../components/footer.php");
?>