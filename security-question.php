<?php
session_start();
include_once "config.php";
if (!isset($_SESSION['create_question'])) {
    header("Location: /login.php");
}

if (isset($_POST['save_squestion'])) {
    $question_id = $_POST['question_id'];
    $answer = $_POST['answer'];
    $user_id = $_SESSION['user_id'];
    $userQuestion = "update users set `squestion` = $question_id , `answer` = '$answer' where user_id = $user_id";
    if (mysqli_query($con, $userQuestion)) {
        $_SESSION['success'] = true;
        $_SESSION['message'] = "Security question added successfully";
        header('Location: /user/dashboard.php');
        exit;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Security Question | <?php echo $_SESSION['site_name']; ?></title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/adminStyle.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
    <link rel="shortcut icon" href="./uploads/settings/<?php echo $_SESSION['favicon']; ?>">
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/toastr/toastr.min.js"></script>
    <?php
    require_once "storeSetting.php"; ?>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">

                <div class="row justify-content-center w-100">
                    <h2 class="text-center">Select Security Question</h2>
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['message']) && isset($_SESSION['success'])) {
                                    $message = $_SESSION['message'];
                                    $success = $_SESSION['success'];
                                    $toastType = $success ? 'success' : 'error';
                                    echo "<script> toastr.$toastType('$message');  </script>";
                                    unset($_SESSION['message']);
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <p class='alert alert-success'><b>Note</b>: Make sure you remember the question and answer. Whenever you <b><i>forget your password</i></b>, you'll be able to reset it with this question.
                                </p>
                                <form action="./security-question.php" method="post">
                                    <?php
                                    $query = "select * from squestions";
                                    $result = $con->query($query);
                                    $questions = array();
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $questions[$row['id']] = $row['name'];
                                        }
                                    } else {
                                        $_SESSION['success'] = true;
                                        $_SESSION['message'] = "Logged in successfully";
                                        header("Location: /user/dashboard.php");
                                    }

                                    ?>
                                    <label for="password" class="form-label">Question</label><span class="text-danger">*</span>
                                    <select class="form-select" name="question_id" id="question_id">
                                        <option value="0">Select Question</option>
                                        <?php
                                        foreach ($questions as $id => $name) {
                                            echo "<option value=\"$id\">$name</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="answer" class="form-label mt-3">Answer</label><span class="text-danger">*</span>
                                    <div class="input-group mb-3">
                                        <input type="text" id="answer" name="answer" class="form-control" placeholder="Answer" value="">
                                    </div>
                                    <button name="save_squestion" value="save_squestion" id="save_squestion" class="btn btn-outline-secondary w-100 py-8 fs-4 mb-2 rounded-2">Save</button>
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
            $("#save_squestion").attr("disabled", true);
            let question_id = 0;

            $("#question_id").change(function() {
                question_id = $("#question_id").val();
                disabledButton();
            })
            let answer = ''
            $("#answer").keyup(function() {
                answer = $("#answer").val();
                disabledButton();
            })

            function disabledButton() {
                if (answer == '' || question_id == '0') {
                    $("#save_squestion").attr("disabled", true);
                } else {
                    $("#save_squestion").attr("disabled", false);
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