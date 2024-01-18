<?php
session_start();
include_once '../config.php';
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
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
$pageTitle = "Managers";
require_once "../components/header.php";
?>

<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <aside class="left-sidebar">
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="<?php echo $appUrl;?>" class="navbar-brand" style="font-size:30px">
          <div class="d-flex align-items-center"><img src="../uploads/settings/<?php echo $_SESSION['logo'] ?>" class="img-fluid" alt="logo" width="50" height="50"><span class="mx-2 my-1" style="font-size:20px"><?php echo $_SESSION['site_name'] ?></span></div>
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="fa fa-times"></i>
        </div>
      </div>
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <div class="sidebar">
          <ul id="sideNav">
          </ul>
        </div>
      </nav>
    </div>
  </aside>

  <!--  Main wrapper -->
  <div class="body-wrapper">

    <!--  Header Start -->
    <?php require_once "../components/profileHeader.php" ?>
    <!--  Header End -->

    <!-- Manager Table start-->
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
          <h3>Managers</h3>
          <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#managerModal" href="#">Create Manager</a>
        </div>
        </div><div class="row dashboard-widget-p-5">
          <div class="container-fluid" style="padding-top:0">
            <table id="dataTable" class="display table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone no.</th>
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
    <!-- Manager Table end-->

  </div>
</div>

<!-- Create Manager modal -->
<div class="container-fluid py-0">
  <div class="modal fade" id="managerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Manager</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form autocomplete="off" action="../queries.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div>
              <label>Profile Picture: </label>
              <input type="file" name="profileImage" id="profileImage" accept="image/jpeg, image/png,image/jpg" class="form-control">
            </div>
            <div>
              <label>Name: </label>
              <input type="text" name="username" id="username" class="form-control" placeholder="Username">
            </div>

            <div class="my-2">
              <label> Email: </label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Email">
            </div>

            <label> Password: </label>
            <div class="my-2">
              <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" value="">
                <span id="changeInputType" class="icon cursor-pointer input-group-text"><i id="eyeToggle" class="fa fa-eye-slash"></i></span>
              </div>
            </div>

            <div class="my-2">
              <label> Phone Number </label>
              <input type="number" name="phone_no" id="phone_no" class="form-control" maxlength="10" placeholder="Phone Number">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn_manager" class="btn btn-outline-secondary">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Create Manager modal End -->

<!-- Modal for View Manager start-->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewUserLabel">View Manager</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table">
            <tbody>
              <tr>
                <td>Username:</td>
                <td id="usernameView"></td>
              </tr>
              <tr>
                <td>Email:</td>
                <td id="emailView"></td>
              </tr>
              <tr>
                <td>Phone no.:</td>
                <td id="phoneView"></td>
              </tr>
              <tr>
                <td>Created On:</td>
                <td id="created_at"></td>
              </tr>
              <tr>
                <td>Updated On:</td>
                <td id="updated_at"></td>
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
<!-- Modal for View Manager end-->

<!-- Edit Manager modal start -->
<div class="modal fade" id="editManagerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Manager</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form autocomplete="off" action="../queries.php" method="POST">
        <div class="modal-body">
          <div>
            <label>Username: </label>
            <input type="text" name="username" id="username1" class="form-control" placeholder="Username">
          </div>

          <div class="my-2">
            <label>Email: </label>
            <input type="email" name="email" id="email1" class="form-control" placeholder="Email">
          </div>

          <div class="my-2">
            <label>Phone No.: </label>
            <input type="text" name="phone_no" id="phone_no1" class="form-control" placeholder="Phone No.">
          </div>
          <div class="my-2">
            <input type="hidden" name="user_id" id="user_id" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="submit" name="manager_update" class="btn btn-outline-secondary">Edit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
  </div>
</div>
<!-- Edit Manager modal end -->

<script>
  $(document).on('click', '.edit_data,.view_data', function() {
    var user_id = $(this).attr("id");
    let value = $(this).attr("value");
    $.ajax({
      url: "../fetch.php",
      method: "POST",
      data: {
        user_id: user_id
      },
      dataType: "json",
      success: function(data) {
        if (value.toLocaleLowerCase() == "edit") {
          $('#username1').val(data[0].username);
          $('#email1').val(data[0].email);
          $('#phone_no1').val(data[0].phone_no);
          $('#user_id').val(data[0].user_id);
          $('#editManagerModal').modal('show');
        } else if (value.toLocaleLowerCase() == "view") {
          $('#usernameView').text(data[0].username);
          $('#emailView').text(data[0].email);
          $('#phoneView').text(data[0].phone_no);
          $('#created_at').text(data[0].created_at);
          $('#updated_at').text(data[0].updated_at);
          $('#viewUserModal').modal('show');
        }
      }
    });
  });

  $(document).ready(function() {
    $('#dataTable').DataTable({
      "ajax": {
        "url": "../fetch.php?allManagers=true",
        "dataSrc": ""
      },
      "bPaginate": true,
      "bFilter": true,
      "bInfo": true,
      "order": [[4, 'desc']],
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, 'All']
      ],
      "columns": [{
          "data": "user_id"
        },
        {
          "data": "username"
        },
        {
          "data": "email"
        },
        {
          "data": "phone_no"
        },
        {
          "render": function(data, type, full, meta) {
            const formattedDate = moment(full.created_at).format("DD/MM/YYYY");
            const formattedTime = moment(full.created_at).format('LT');
            return `<span class="badge bg-light-info"><div class="mb-1">${formattedTime}</div><div>${formattedDate}</div></span>`;
          }
        },
        {
          "render": function(data, type, full, meta) {
            return '<button name="view" title="View Manager" value="view" id="' + full.user_id + '" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></button>' +
              '<button name="edit" title="Edit Manager" value="Edit" id="' + full.user_id + '" class="btn btn-info edit_data"><span class="fa fa-pencil"></span></button>' +
              '<button value=' + full.user_id + ' class="managerDelete btn btn-danger mx-1" title="Delete Manager" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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

  $(document).on('click', '.managerDelete', function() {
    let val = $(this).val();
    Swal.fire({
      text: 'Are you sure want to delete this "Manager"?',
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
            user_id: val,
            user_delete: true
          },
          success: function(response) {
            if (response == 1) {
              toastr.success("Manager deleted successfully");
              setTimeout(function() {
                window.location.reload();
              }, 1000);
            } else {
              toastr.error("Error while deleting Manager");
            }
          },
          error: function(xhr, textStatus, errorThrown) {
            toastr.error(errorThrown);
          }
        });
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
      var username = document.getElementById("username").value.trim();
      var email = document.getElementById("email").value.trim();
      var password = document.getElementById("password").value.trim();
      var phone_no = document.getElementById("phone_no").value.trim();
      var emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

      if (username === "") {
        toastr.error("Please enter a username.");
        event.preventDefault();
        return;
      }

      if (email === "") {
        toastr.error("Please enter an email.");
        event.preventDefault();
        return;
      } else if (!emailRegex.test(email)) {
        toastr.error("Please enter a valid email address.");
        event.preventDefault();
        return;
      }

      if (password === "") {
        toastr.error("Please enter a password.");
        event.preventDefault();
        return;
      }

      if (phone_no === "") {
        toastr.error("Please enter a phone number.");
        event.preventDefault();
        return;
      }
    });
  });
</script>
<script src="./admin.js"></script>
<?php
require_once("../components/footer.php");
?>