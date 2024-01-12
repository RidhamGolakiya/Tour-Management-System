<?php
session_start();
include_once '../config.php';
if (!isset($_SESSION['user'])) {
  header("location: $appUrl/login.php");
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != 1) {
  setcookie('user', '', time() - 3600, '/');
  $_SESSION['success'] = false;
  $_SESSION['message'] = "You are not authorized to access the admin site.";
  header("location: $appUrl/login.php");
  exit;
}
$pageTitle = "Transactions";
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
        <h3>Transactions</h3>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="container-fluid" style="padding-top:0">
        <table id="dataTable" class="display table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Booking Id</th>
              <th>Amount</th>
              <th>Payment Status</th>
              <th>Payment Type</th>
              <th>Payment Date</th>
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
    const formattedPrice = (price) => {
      return new Intl.NumberFormat('en-IN').format(price)
    };
    $('#dataTable').DataTable({
      "ajax": {
        "url": "../fetch.php?transactions=true",
        "dataSrc": ""
      },
      "bPaginate": true,
      "bFilter": true,
      "bInfo": true,
      aaSorting: [
        [1, 'desc']
      ],
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, 'All']
      ],
      "columns": [{
          "data": "id"
        },
        {
          "render": function(data, type, full, meta) {
            return `<span class="badge bg-light-danger-custom">${full.booking_id}</span>`;
          }
        },
        {
          "data": "amount"
        },
        {
          "render": function(data, type, full, meta) {
            const statusText = full.payment_status == null && full.payment_type == '0' ? "Completed" : full.payment_status == null && full.payment_type != '0' ? 'Failed' : full.payment_status;
            const badgeClass = full.payment_status == null && full.payment_type == '0' ? "bg-info" : full.payment_status == null && full.payment_type != '0' ? 'bg-danger' : "bg-info";
            return `<span class="badge ${badgeClass}"><div class="mb-1">${statusText}</div></span>`;
          }
        },
        {
          "render": function(data, type, full, meta) {
            const statusText = full.payment_type == 1 ? "Stripe" : "Manually";
            const badgeClass = full.payment_type == 1 ? "bg-success" : "bg-info";
            return `<span class="badge ${badgeClass}"><div class="mb-1">${statusText}</div></span>`;
          }
        },
        {
          "render": function(data, type, full, meta) {
            const formattedDate = moment(full.created_at).format("DD/MM/YYYY");
            const formattedTime = moment(full.created_at).format('LT');
            return `<span class="badge bg-light-info"><div class="mb-1">${formattedTime}</div><div>${formattedDate}</div></span>`;
          }
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

<script src="./admin.js"></script>

<?php
require_once("../components/footer.php");
?>