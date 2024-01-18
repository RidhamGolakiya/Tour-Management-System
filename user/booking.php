<?php
session_start();
include_once '../config.php';
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
}
// Check user has admin role or not
else if (isset($_SESSION["role"]) && $_SESSION["role"] != 0) {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the user site.";
    header("location: $appUrl/login.php");
    exit;
}
$pageTitle = "Bookings";
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
                <h3>Package Booking</h3>
            </div>
        </div>
        <div class="row dashboard-widget-p-5">
            <div class="container-fluid" style="padding-top:0">
                <table id="dataTable" class="display table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking Id</th>
                            <th>Package Name</th>
                            <th>Price Per Person</th>
                            <th>Total Person</th>
                            <th>Total Paid</th>
                            <th>Status</th>
                            <th>Booked At</th>
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
</div>

<!-- Modal for View Booking start-->
<div class="modal fade" id="viewBookingDetails" tabindex="-1" role="dialog" aria-labelledby="bookingDetailsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsLabel">Booking Details</h5>
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
                                <td>Package Name: </td>
                                <td id="packagenameView"></td>
                            </tr>
                            <tr>
                                <td>Email: </td>
                                <td id="emailView"></td>
                            </tr>
                            <tr>
                                <td>Price Per Person: </td>
                                <td class="fw-bolder" id="package_price"></td>
                            </tr>
                            <tr>
                                <td>Total Person: </td>
                                <td class="fw-bolder" id="total_person"></td>
                            </tr>
                            <tr>
                                <td>Total Paid: </td>
                                <td class="fw-bolder" id="total_paid"></td>
                            </tr>
                            <tr>
                                <td>Booking On:</td>
                                <td id="booking_date"></td>
                            </tr>
                            <tr id="cancel_label">
                                <td>Cancelled On:</td>
                                <td id="cancel_date"></td>
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
        const formattedPrice = (price) => {
            return new Intl.NumberFormat('en-IN').format(price)
        };
        const user_id = <?php echo $_SESSION['user_id']; ?>;
        // Datatable start
        $('#dataTable').DataTable({
            "ajax": {
                "url": `../fetch.php?user_booking=true&user_id=${user_id}`,
                "dataSrc": ""
            },
            "bPaginate": true,
            "bFilter": true,
            "bInfo": true,
            "order": [
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
                    "data": "tour_name"
                },
                {
                    "render": function(data, type, full, meta) {
                        return `<span>₹ ${formattedPrice(full.price)}</span>`;
                    }
                },
                {
                    "data": "total_person"
                },
                {
                    "render": function(data, type, full, meta) {
                        return `<span>₹ ${formattedPrice(parseFloat(full.price)*parseFloat(full.total_person))}</span>`;
                    }
                },
                {
                    "render": function(data, type, full, meta) {
                        const role = <?php if (isset($_SESSION['role'])) {
                                            echo $_SESSION['role'];
                                        } else {
                                            echo "null";
                                        } ?>;
                        const status = full.status == 1 ? 'Booked' : 'Cancelled';
                        const message = role == 1 ? "Booking Cancelled by You" : "Booking Cancelled by Admin";
                        const userMessage = role == 0 ? "Booking Cancelled by You" : "Booking Cancelled by User";

                        let tooltip = ''; // Initialize the tooltip content
                        if (full.status == 0) {
                            tooltip = `<span class="mx-1 tool_tip remarks-tooltip" data-canceledby="${full.cancel_by}" data-toggle="tooltip" data-placement="top" title="Click for details" data-remarks="${full.cancel_remarks.length > 0 ? full.cancel_remarks : ''}">?<span class="pricetooltip">${full.cancel_by == 1 ? message : userMessage}</span></span>`;
                        }
                        return `<span class="badge ${full.status == 1 ? 'bg-success' : 'bg-danger'}">${status}</span>${tooltip}`;
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
                        const id = full.id;
                        return `<button name="view" title="View Booking Details" value="view" id=${id} class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></button>` +
                            `${full.status != 0 ? '<button name="cancel" title="Cancel Booking" value="'+id+'" id="cancelBookingBtn"  class="btn btn-danger mx-1"><span class="fa fa-xmark"></span></button>' : ''}`;
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

        $(document).on('click', '.view_data', function() {
            var booking_id = $(this).attr("id");
            $.ajax({
                url: "../fetch.php",
                method: "POST",
                data: {
                    booking_id: booking_id
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#usernameView').text(data[0].username);
                    $('#emailView').text(data[0].email);
                    $('#packagenameView').text(data[0].tour_name);
                    $('#package_price').text(`₹ ${formattedPrice(data[0].price)}`);
                    $('#total_person').text(data[0].total_person);
                    $('#total_paid').text(`₹ ${formattedPrice(parseFloat(data[0].price)*parseFloat(data[0].total_person))}`);
                    $('#booking_date').text(moment(data[0].created_at).format('LLLL'));
                    if (data[0].cancel_at) {
                        $('#cancel_label').show();
                        $('#cancel_date').text(moment(data[0].updated_at).format('LLLL'));
                    } else {
                        $('#cancel_label').hide();
                    }
                    $('#viewBookingDetails').modal('show');
                }
            });
        });
    });

    $(document).on("click", '#cancelBookingBtn', function() {
        var booking_id = $(this).val();
        $('#booking_cancel_id').val(booking_id); // Set the value in the hidden input

        // Show a SweetAlert input dialog to get remarks from the user
        Swal.fire({
                title: 'Cancel Booking',
                input: 'textarea',
                inputLabel: 'Remarks',
                inputPlaceholder: 'Add remarks here...',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'No, keep it',
                showLoaderOnConfirm: true,
                preConfirm: (remarks) => {
                    if (!remarks) {
                        Swal.showValidationMessage('Remarks cannot be empty');
                    } else {
                        // Handle the confirmation after the user enters remarks
                        return $.ajax({
                                url: "../queries.php",
                                method: "POST",
                                data: {
                                    cancelBooking: true,
                                    booking_id: booking_id,
                                    remarks: remarks // Include the remarks data
                                }
                            })
                            .then((response) => {
                                response = JSON.parse(response);
                                if (response.success) {
                                    return response.message;
                                } else {
                                    throw new Error(response.message);
                                }
                            })
                    }
                }
            })
            .then((result) => {
                if (result.isConfirmed) {
                    // Display a SweetAlert success message
                    Swal.fire('Success', result.value, 'success').then(() => {
                        window.location.reload(); // Refresh the page
                    });
                }
            });
    });

    $(document).on('click', '.remarks-tooltip', function() {
        const role = <?php if (isset($_SESSION['role'])) {
                            echo $_SESSION['role'];
                        } else {
                            echo "null";
                        } ?>;
        const remarks = $(this).data('remarks');
        const canceledBy = $(this).data('canceledby');
        const message = role == 1 ? "You" : "Admin";
        const userMessage = role == 0 ? "You" : "User";

        const alertContent = `
        <div>
            <p><strong>Remarks:</strong> ${remarks}</p>
            <p><strong>Cancelled by:</strong> ${canceledBy == 1 ? message : userMessage}</p>
        </div>
    `;

        if (remarks.length > 0) {
            Swal.fire({
                title: 'Cancellation Details',
                html: alertContent,
                confirmButtonText: 'OK',
                showCancelButton: false
            });
        }
    });
</script>

<script src="../assets/js/script.js"></script>
<?php
require_once "../components/footer.php";
?>