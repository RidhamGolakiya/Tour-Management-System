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
$pageTitle = "Enquiries";
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
        <h3>Enquiries</h3>
      </div>
      </div><div class="row dashboard-widget-p-5">
        <div class="container-fluid" style="padding-top:0">
          <table id="dataTable" class="display table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile No.</th>
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

  <!-- Modal for View Booking start-->
  <div class="modal fade" id="enquiriesModal" tabindex="-1" role="dialog" aria-labelledby="enquiryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="enquiryLabel">Booking Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <td>Username: </td>
                  <td id="usernameView"></td>
                </tr>
                <tr>
                  <td>Email: </td>
                  <td id="emailView"></td>
                </tr>
                <tr>
                  <td>Mobile no.: </td>
                  <td id="phoneView"></td>
                </tr>
                <tr>
                  <td>Message: </td>
                  <td id="message"></td>
                </tr>
                <tr>
                  <td>Enquiry On:</td>
                  <td id="created_at"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal for View Booking end-->

  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable({
        "ajax": {
          "url": "../fetch.php?contact_us=true",
          "dataSrc": ""
        },
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        aaSorting: [
          [4, 'desc']
        ],
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, 'All']
        ],
        "columns": [{
            "data": "id"
          },
          {
            "data": "name"
          },
          {
            "data": "email"
          },
          {
            "data": "mobile"
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
              return '<button name="view" title="View User" value="view" id="' + full.id + '" class="btn btn-warning mx-1 view_enquiry"><span class="fa fa-eye"></span></button>' +
                '<button value=' + full.id + ' class="contact_us btn btn-danger mx-1" title="Delete enquiry" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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

    $(document).on('click', '.contact_us', function() {
      let val = $(this).val();
      Swal.fire({
        text: 'Are you sure want to delete this "Enquiry"?',
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
              delete_enquiry: true,
              enquiry_id: val
            },
            success: function(response) {
              if (response == 1) {
                toastr.success("Enquiry deleted successfully");
                setTimeout(function() {
                  window.location.reload();
                }, 1000);
              } else {
                toastr.error("Error while deleting Enquiry");
              }
            },
            error: function(xhr, textStatus, errorThrown) {
              toastr.error(errorThrown);
            }
          });
        }
      });
    });

    $(document).on('click', '.view_enquiry', function() {
      var enquiry_id = $(this).attr("id");
      let value = $(this).attr("value");
      $.ajax({
        url: "../fetch.php",
        method: "POST",
        data: {
          enquiry_id: enquiry_id
        },
        dataType: "json",
        success: function(data) {
          $('#usernameView').text(data[0].name);
          $('#emailView').text(data[0].email);
          $('#phoneView').text(data[0].mobile);
          $('#message').text(data[0].message);
          $('#created_at').text(moment(data[0].created_at).format('LLLL'));
          $('#enquiriesModal').modal('show');
        }
      });
    });

    toastr.options = {
      positionClass: "toast-top-right",
      timeOut: 2000,
      progressBar: true
    };
  </script>

  <script src="./manager.js"></script>

  <?php
  require_once("../components/footer.php");
  ?>