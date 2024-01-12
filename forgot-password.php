<?php
session_start();
include_once "config.php";

if (isset($_POST['change_password'])) {
    $email = $_POST['email'];
    $answer = $_POST['answer'];
    $question_id = $_POST['question_id'];
    $userQuestion = "select * from users where email='$email' and answer COLLATE utf8_bin = '$answer' and squestion=$question_id";
    $result = mysqli_query($con, $userQuestion);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $showPasswordFields = true;
        $_SESSION['change_user_id'] = $row['user_id'];
        $_SESSION['authorized_change_password'] = true;
    } else {
        $errorMessage = "Invalid email, answer, or security question.";
    }
} else if (isset($_POST['btn_change_password'])) {

    $user_id = isset($_SESSION['change_user_id']) ? $_SESSION['change_user_id'] : '';
    $password = isset($_POST['password']) ?  $_POST['password'] : '';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $changePassword = "UPDATE users SET password = '$hashedPassword' WHERE user_id = $user_id";
    $result = mysqli_query($con, $changePassword);
    if ($result) {
        unset($_SESSION['authorized_change_password']);
        unset($_SESSION['change_user_id']);
        $_SESSION['success'] = true;
        $_SESSION['message'] = "Password changed successfully";
    } else {
        $errorMessage = "An error occurred while changing the password.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | <?php echo $_SESSION['site_name']; ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/adminStyle.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/toastr/toastr.min.js"></script>
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <?php
    require_once "storeSetting.php"; ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">

                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h3 class="text-center mb-3">Forgot Password</h3>
                                <?php
                                if (isset($_SESSION['message']) && isset($_SESSION['success'])) {
                                    $message = $_SESSION['message'];
                                    $success = $_SESSION['success'];
                                    $toastType = $success ? 'success' : 'error';
                                    echo "<script> toastr.$toastType('$message');  setTimeout(function() {
                                        window.location.href = '/login.php';
                                     },1000)</script>";
                                    unset($_SESSION['message']);
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <form action="./forgot-password.php" method="post">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label><span class="text-danger">*</span>
                                        <input type="email" name="email" class="form-control" value="<?php if (isset($showPasswordFields) && $showPasswordFields) {
                                                                                                            echo $email;
                                                                                                        } ?>" placeholder="Email" id="email">
                                    </div>
                                    <label for="password" class="form-label">Question</label><span class="text-danger">*</span>
                                    <select class="form-select" name="question_id" id="question_id">
                                        <option value="0">Select Question</option>
                                        <?php
                                        $query = "select * from squestions";
                                        $result = $con->query($query);
                                        $questions = array();
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $questions[$row['id']] = $row['name'];
                                        }
                                        $selectedQuestion = '';
                                        if (isset($showPasswordFields) && $showPasswordFields) {
                                            $selectedQuestion = $question_id;
                                        }
                                        foreach ($questions as $id => $name) {
                                            $setSelect = $selectedQuestion == $id ? "selected" : "";
                                            echo "<option value=\"$id\" $setSelect>$name</option>";
                                        }
                                        ?>

                                    </select>
                                    <label for="answer" class="form-label mt-3">Answer</label><span class="text-danger">*</span>
                                    <div class="input-group mb-3">
                                        <input type="text" id="answer" name="answer" class="form-control" placeholder="Answer" value="<?php if (isset($showPasswordFields) && $showPasswordFields) {
                                                                                                                                            echo $answer;
                                                                                                                                        } ?>">
                                    </div>
                                    <?php if (isset($showPasswordFields) && $showPasswordFields) : ?>
                                        <label for="password" class="form-label mt-2">New Password</label><span class="text-danger">*</span>
                                        <div class="input-group mb-3">
                                            <input type="password" id="password" name="password" class="form-control" placeholder="New Password" value="" required>
                                        </div>

                                        <label for="confirm_password" class="form-label mt-2">Confirm Password</label><span class="text-danger">*</span>
                                        <div class="input-group mb-3">
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="" required>
                                        </div>
                                        <script>
                                            document.getElementById("answer").disabled = true;
                                            document.getElementById("question_id").disabled = true;
                                            document.getElementById("email").disabled = true;
                                        </script>
                                        <button name="btn_change_password" id="btn_change_password" class="btn btn-outline-secondary w-100 py-8 fs-4 mb-2 rounded-2">Change Password</button>
                                    <?php elseif (isset($errorMessage)) : ?>
                                        <p class="text-danger"><?php echo $errorMessage; ?></p>
                                        <button name="change_password" value="change_password" id="change_password" class="btn btn-outline-secondary w-100 py-8 fs-4 mb-2 rounded-2">Verify</button>
                                    <?php else : ?>
                                        <button name="change_password" value="change_password" id="change_password" class="btn btn-outline-secondary w-100 py-8 fs-4 mb-2 rounded-2">Verify</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#change_password").attr("disabled", true);
            let question_id = 0;

            $("#question_id").change(function() {
                question_id = $("#question_id").val();
                disabledButton();
            })
            let answer = ''
            let email = ''
            $("#answer,#email").keyup(function() {
                answer = $("#answer").val();
                email = $("#email").val();
                disabledButton();
            })

            function disabledButton() {
                if (answer == '' || email == '' || question_id == '0') {
                    $("#change_password").attr("disabled", true);
                } else {
                    $("#change_password").attr("disabled", false);
                }
            }

            let password = '';
            let confirm_password = '';
            disabledPasswordButton();
            $("#password,#confirm_password").keyup(function() {
                password = $("#password").val();
                confirm_password = $("#confirm_password").val();
                disabledPasswordButton();
            })

            function disabledPasswordButton() {
                if (password == '' || confirm_password == '' || password != confirm_password) {
                    $("#btn_change_password").attr("disabled", true);
                } else {
                    $("#btn_change_password").attr("disabled", false);
                }
            }

        });

        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 1000,
            progressBar: true
        };
    </script>

    <script src="./assets/bootstrap/bootstrap.min.js"></script>
    <script src="./assets/js/script.js"></script>
    <script src="./assets/font-awesome/all.min.js"></script>
</body>

</html>