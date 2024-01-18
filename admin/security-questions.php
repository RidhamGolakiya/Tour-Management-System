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
$pageTitle = "Security Questions";
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

    <!-- User Table start-->
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
          <h3>Security Question</h3>
          <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#questionModal" href="#">Create Question</a>
        </div>
        </div><div class="row dashboard-widget-p-5">
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
    <!-- User Table end-->

  </div>
</div>

 <!-- Create User modal -->
 <div class="container-fluid py-0">
      <div class="modal fade" id="questionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Create Question</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form autocomplete="off" action="../queries.php" method="POST">
              <div class="modal-body">
                <div>
                  <label>Name: </label>
                  <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="modal-footer">
                <button type="submit" name="btn_question" class="btn btn-outline-secondary">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Create user modal End -->

<!-- Modal for View User start-->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewUserLabel">View Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table">
            <tbody>
              <tr>
                <td>Name:</td>
                <td id="nameView"></td>
              </tr>
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
<!-- Modal for View User end-->

<!-- Edit user modal start -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Qustion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form autocomplete="off" action="../queries.php" method="POST">
        <div class="modal-body">
        <input type="hidden" name="id" id="question_id">
          <div>
            <label>Name: </label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn_edit_question" class="btn btn-outline-secondary">Edit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
  </div>
</div>
<!-- Edit user modal end -->

<script>
  $(document).on('click', '.edit_data,.view_data', function() {
    var question_id = $(this).attr("id");
    let value = $(this).attr("value");
    $.ajax({
      url: "../fetch.php",
      method: "POST",
      data: {
        question_id: question_id
      },
      dataType: "json",
      success: function(data) {
        if (value.toLocaleLowerCase() == "edit") {
          $('#name').val(data[0].name);
          $('#question_id').val(data[0].id);
          $('#editUserModal').modal('show');
        } else if (value.toLocaleLowerCase() == "view") {
          $('#nameView').text(data[0].name);
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
        "url": "../fetch.php?allQuestion=true",
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
              const formattedDate = moment(full.created_at).format('L');
              const formattedTime = moment(full.created_at).format('LT');
              return `<span class="badge bg-light-info"><div class="mb-1">${formattedTime}</div><div>${formattedDate}</div></span>`;
            }
          },
        {
          "render": function(data, type, full, meta) {
            return '<button name="view" title="View Question" value="view" id="' + full.id + '" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></button>' +
              '<button name="edit" title="Edit Question" value="Edit" id="' + full.id + '" class="btn btn-info edit_data"><span class="fa fa-pencil"></span></button>' +
              '<button value=' + full.id + ' class="questionDelete btn btn-danger mx-1" title="Delete Question" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
          },
          "orderable": false
        }
      ],
      "columnDefs": [{
        "targets": 0,
        "visible": false,
        "searchable": false
      }]
    });
  });

  $(document).on('click', '.questionDelete', function() {
    let val = $(this).val();
    Swal.fire({
      text: 'Are you sure want to delete this "Question"?',
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
              question_id: val,
              delete_question:true
            },
            success: function(response) {
              if (response == 1) {
                toastr.success("Question deleted successfully");
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
<script src="./admin.js"></script>
<?php
require_once("../components/footer.php");
?>