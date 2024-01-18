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
$pageTitle = "Countries";
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

    <!-- Country Table start-->
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
          <h3>Countries</h3>
          <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#countryModal" href="#">Add Country</a>
        </div>
      </div>
      <div class="row dashboard-widget-p-5">
        <div class="container-fluid" style="padding-top:0">
          <table id="dataTable" class="display table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
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
  <!-- Country Table end-->

</div>

<!-- Add Country modal -->
<div class="container-fluid py-0">
  <div class="modal fade" id="countryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Country</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form autocomplete="off" action="../queries.php" method="POST" id="addCountryForm">
          <div class="modal-body">
            <div>
              <label>Country Name:</label><span class="text-danger">*</span>
              <input type="text" name="country_name" class="form-control mt-2" placeholder="Country Name" id="name">
            </div>
            <div class="modal-footer">
              <button type="submit" name="country_save" class="btn btn-outline-secondary">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Add Country modal End -->

<!-- Edit Country modal -->
<div class="container-fluid py-0">
  <div class="modal fade" id="editCountryModal" tabindex="-1" role="dialog" aria-labelledby="editCountryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Country</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form autocomplete="off" action="../queries.php" method="POST" id="editCountryForm">
          <div class="modal-body">
            <div>
              <label>Country Name:</label><span class="text-danger">*</span>
              <input type="text" name="country_name" class="form-control mt-2" placeholder="Country Name" id="country_name">
            </div>
            <div class="my-2">
              <input type="hidden" name="country_id" id="country_id" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="submit" name="country_edit" class="btn btn-outline-secondary">Edit</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Edit Country modal End -->

<script>
  $(document).on('click', '.edit_data', function() {
    var country_id = $(this).attr("id");
    $.ajax({
      url: "../fetch.php",
      method: "POST",
      data: {
        country_id: country_id,
        country_edit: true
      },
      dataType: "json",
      success: function(data) {
        $('#country_name').val(data[0].name);
        $('#country_id').val(data[0].id);
        $('#editCountryModal').modal('show');
      }
    });
  });

  $(document).ready(function() {
    $('#dataTable').DataTable({
      "ajax": {
        "url": "../fetch.php?allCountries=true",
        "dataSrc": ""
      },
      "bPaginate": true,
      "bFilter": true,
      "bInfo": true,
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
          "render": function(data, type, full, meta) {
            const formattedDate = moment(full.created_at).format("DD/MM/YYYY");
            const formattedTime = moment(full.created_at).format('LT');
            return `<span class="badge bg-light-info"><div class="mb-1">${formattedTime}</div><div>${formattedDate}</div></span>`;
          }
        },
        {
          "render": function(data, type, full, meta) {
            return '<button name="edit" title="Edit Country" value="Edit" id="' + full.id + '" class="btn btn-info edit_data"><span class="fa fa-pencil"></span></button>' +
              '<button value=' + full.id + ' class="countryDelete btn btn-danger mx-1" title="Delete Country" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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

  $(document).on('click', '.countryDelete', function() {
    let val = $(this).val();
    Swal.fire({
      text: 'Are you sure want to delete this "Country"?',
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
            country_id: val,
            country_delete: true
          },
          success: function(response) {
            if (response == 1) {
              toastr.success("Country deleted successfully");
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

  document.addEventListener("DOMContentLoaded", function() {
    var editCountryForm = document.getElementById("editCountryForm");
    var addCountryForm = document.getElementById("addCountryForm");

    editCountryForm.addEventListener("submit", function(event) {
      var countryInput = document.getElementById("country_name");

      if (!countryInput.value.trim()) {
        toastr.error("Please enter country name.");
        event.preventDefault();
      }
    });

    addCountryForm.addEventListener("submit", function(event) {
      var countryNameInput = document.getElementById("name");

      if (!countryNameInput.value.trim()) {
        toastr.error("Please enter country name.");
        event.preventDefault();
      }
    });
  });
</script>

<script src="./admin.js"></script>
<?php
require_once("../components/footer.php");
?>