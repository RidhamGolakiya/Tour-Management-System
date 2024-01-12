<?php
include_once 'config.php';
session_start();

function redirectTo($path, $message, $error)
{
  if ($_SESSION['role'] == 1) {
    if ($error == true) {
      $_SESSION['success'] = false;
      $_SESSION['message'] = "$message";
      header("location: /admin/$path");
    } else {
      $_SESSION['success'] = true;
      $_SESSION['message'] = "$message";
      header("location: /admin/$path");
    }
  } else if ($_SESSION['role'] == 2) {
    if ($error == true) {
      $_SESSION['success'] = false;
      $_SESSION['message'] = "$message";
      header("location: /manager/$path");
    } else {
      $_SESSION['success'] = true;
      $_SESSION['message'] = "$message";
      header("location: /manager/$path");
    }
  }
}


// ----------------------- Create new User -----------------------
if (isset($_POST['save'])) {
  if (!file_exists('./uploads/users')) {
    mkdir('./uploads/users', 0777, true);
  }
  $imageName = 'NULL';
  $targetFile = "uploads/users/" . basename($_FILES["profileImage"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  $allowedFormats = array("jpg", "jpeg", "png", "gif");
  // File type checking
  if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
    redirectTo("users.php", "Only JPG, JPEG, PNG, and GIF files are allowed.", true);
    $uploadOk = 0;
  }
  if ($uploadOk == 0 && $imageFileType) {
    redirectTo("users.php", "File was not uploaded..", true);
  } else {
    // Move file to the location
    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
      $imageName = $_FILES["profileImage"]["name"];
    }
  }

  if ($uploadOk == 1) {
    if (
      (isset($_POST['username']) && empty($_POST['username'])) ||
      (isset($_POST['email']) && empty($_POST['email'])) ||
      (isset($_POST['password']) && empty($_POST['password'])) ||
      (isset($_POST['phone_no']) && empty($_POST['phone_no']))
    ) {
      redirectTo("users.php", "All fields are required.", true);
      exit;
    }
    $username = $_POST['username'] ? ucwords($_POST['username']) : '';
    $email = $_POST['email'] ? $_POST['email'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '123456';
    $phone_no = $_POST['phone_no'] ?  $_POST['phone_no'] : '';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check user already exists or not
    $checkExistance = "SELECT * FROM users WHERE email = '$email'";
    $isUser = mysqli_query($con, $checkExistance);
    if (mysqli_num_rows($isUser) > 0) {
      redirectTo("users.php", "This email is already exists", true);
    }
    // Insert query
    $sql = "INSERT INTO users (`username`,`image`,`email`,`password`,`phone_no`) VALUES ('$username','$imageName', '$email', '$hashedPassword',$phone_no)";
    $query_run = mysqli_query($con, $sql);
    if ($query_run) {
      redirectTo("users.php", "User created successfully", false);
      exit;
    } else {
      echo mysqli_error($con);
    }
  }
}

// ----------------------- Update user -----------------------
else if (isset($_POST["user_update"])) {
  $id =  $_POST["user_id"];
  $username = ucwords($_POST['username']);
  $email = $_POST['email'];
  $phone_no = $_POST['phone_no'];
  // Update Query
  $sql = "UPDATE users SET username = '$username',email = '$email',phone_no = '$phone_no' WHERE user_id = $id";
  $query_run = mysqli_query($con, $sql);
  if ($query_run) {
    redirectTo("users.php", "User updated successfully", false);
    exit;
  }
}

// ----------------------- Delete user -----------------------
else if (isset($_POST["user_id"]) && isset($_POST['user_delete']) && $_POST['user_delete'] == true) {
  $id =  $_POST["user_id"];
  $sql = "DELETE FROM users WHERE user_id= $id";
  $delete_result = mysqli_query($con, $sql);

  if ($delete_result) {
    echo 1;
  } else {
    $error_message = mysqli_error($con);
    // Check if the error message contains keywords related to foreign key constraints
    if (strpos($error_message, "foreign key constraint") !== false) {
      echo "User has booked trip package so can't deleted.";
    } else {
      echo "An error occurred while deleting the record: $error_message";
    }
  }
}

// ----------------------- Create Manager -----------------------

else if (isset($_POST['btn_manager'])) {
  if (!file_exists('./uploads/users/')) {
    mkdir('./uploads/users/', 0777, true);
  }
  $targetFile = "uploads/users/" . basename($_FILES["profileImage"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  $allowedFormats = array("jpg", "jpeg", "png", "gif");
  // File type checking
  if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
    redirectTo("managers.php", "Only JPG, JPEG, PNG, and GIF files are allowed.", true);
    $uploadOk = 0;
  }

  if ($uploadOk == 0 && $imageFileType) {
    redirectTo("managers.php", "File was not uploaded.", true);
  } else {
    // Move file to the location
    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
      $imageName = $_FILES["profileImage"]["name"];
    }
  }

  if ($uploadOk == 1) {
    if (
      (isset($_POST['username']) && empty($_POST['username'])) ||
      (isset($_POST['email']) && empty($_POST['email'])) ||
      (isset($_POST['password']) && empty($_POST['password'])) ||
      (isset($_POST['phone_no']) && empty($_POST['phone_no']))
    ) {
      redirectTo("users.php", "All fields are required.", true);
      exit;
    }
    $username = $_POST['username'] ? ucwords($_POST['username']) : '';
    $email = $_POST['email'] ? $_POST['email'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '123456';
    $phone_no = $_POST['phone_no'] ?  $_POST['phone_no'] : '';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check user already exists or not
    $checkExistance = "SELECT * FROM users WHERE email = '$email'";
    $isUser = mysqli_query($con, $checkExistance);
    if (mysqli_num_rows($isUser) > 0) {
      unset($_SESSION['message']);
      redirectTo("managers.php", "This email is already exists.", true);
    }
    // Insert query
    $sql = "INSERT INTO users (`username`,`image`,`email`,`role`,`password`,`phone_no`) VALUES ('$username','$imageName', '$email',2,'$hashedPassword',$phone_no)";
    $query_run = mysqli_query($con, $sql);
    if ($query_run) {
      redirectTo("managers.php", "Manager created successfully.", false);
      exit;
    } else {
      redirectTo("managers.php", mysqli_error($con), true);
    }
  }
}

// ----------------------- Update manager -----------------------
else if (isset($_POST["manager_update"])) {
  $id =  $_POST["user_id"];
  $username = ucwords($_POST['username']);
  $email = $_POST['email'];
  $phone_no = $_POST['phone_no'];
  // Update Query
  $sql = "UPDATE users SET username = '$username',email = '$email',phone_no = '$phone_no' WHERE user_id = $id";
  $query_run = mysqli_query($con, $sql);
  if ($query_run) {
    redirectTo("managers.php", "Manager updated successfully", false);
    exit;
  }
}

// ----------------------- Save tour Package -----------------------

else if (isset($_POST['pack_save'])) {
  $imageName = '';
  $targetDirectory = "./uploads/tours/";
  if (!file_exists($targetDirectory)) {
    mkdir($targetDirectory, 0777, true);
  }
  $newFileName = uniqid() . "_" . basename($_FILES["image"]["name"]); // Generate a unique name
  $targetFile = $targetDirectory . $newFileName;

  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // File type checking
  $allowedFormats = array("jpg", "jpeg", "png", "gif");
  if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
    redirectTo("tour-packages.php", "Only JPG, JPEG, PNG, and GIF files are allowed.", true);
    exit;
  } else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
      $imageName = $newFileName;
    } else {
      $_SESSION['success'] = false;
      $_SESSION['message'] = "Error uploading file.";
    }
  }

  if (
    (isset($_POST['tour_name']) && empty($_POST['tour_name'])) ||
    (isset($_POST['description']) && empty($_POST['description'])) ||
    (isset($_POST['price']) && empty($_POST['price'])) ||
    (isset($_POST['state_name']) && empty($_POST['state_name'])) ||
    (isset($_POST['country_name']) && empty($_POST['country_name'])) ||
    (isset($_POST['other_details']) && empty($_POST['other_details']))
  ) {
    redirectTo("tour-packages.php", "All fields are required.", true);
    exit;
  }

  $tour_name = $_POST["tour_name"];
  $description = $_POST["description"];
  $price = $_POST["price"];
  $state_name = $_POST["state_name"];
  $country_name = $_POST["country_name"];
  $other_details = $_POST["other_details"];
  $tour_name = str_replace("'", "''", $tour_name);
  $description = str_replace("'", "''", $description);
  $other_details = str_replace("'", "''", $other_details);
  // insert package query
  $sql = "INSERT INTO tour_packages (tour_name, description, images,country_name,state_name, price, other_details) VALUES ('$tour_name', '$description', '$imageName','$country_name','$state_name', $price, '$other_details')";
  $result = mysqli_query($con, $sql);
  echo $sql;

  if ($result) {
    redirectTo("tour-packages.php", "Tour package created successfully.", false);
  } else {
    redirectTo("tour-packages.php", mysqli_error($con), true);
  }
}

// -----------------------  Update Tour Package -----------------------

else if (isset($_POST["updt_pkg"])) {

  $imageName = $_POST["old_images"];
  $oldImage = $_POST["old_images"];

  $targetDirectory = "./uploads/tours/";
  if (!file_exists($targetDirectory)) {
    mkdir($targetDirectory, 0777, true);
  }
  $newFileName = uniqid() . "_" . basename($_FILES["image"]["name"]); // Generate a unique name
  $targetFile = $targetDirectory . $newFileName;

  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // File type checking
  $allowedFormats = array("jpg", "jpeg", "png", "gif");
  if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
    redirectTo("tour-packages.php", "Only JPG, JPEG, PNG, and GIF files are allowed.", true);
    exit;
  } else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
      $imageName = $newFileName;
      unlink("./uploads/tours/" . $oldImage);
    } else {
      $_SESSION['success'] = false;
      $_SESSION['message'] = "Error uploading file.";
    }
  }

  $id = $_POST["id"];
  $tour_name = $_POST["tour_name"];
  $description = $_POST["description"];
  $country_name = $_POST['country_name'];
  $state_name = $_POST['state_name'];
  $price = $_POST["price"];
  $other_details = $_POST["other_details"];
  $tour_name = str_replace("'", "''", $tour_name);
  $description = str_replace("'", "''", $description);
  $other_details = str_replace("'", "''", $other_details);

  // Update package query
  $update_sql = "UPDATE tour_packages SET images='$imageName',tour_name='$tour_name', description='$description',country_name='$country_name',state_name='$state_name', price=$price, other_details='$other_details' WHERE tour_id=$id";
  $update_result = mysqli_query($con, $update_sql);
  if ($update_result) {
    redirectTo("tour-packages.php", "Tour package details updated successfully.", false);
    exit;
  } else {
    redirectTo("tour-packages.php", mysqli_error($con), true);
  }
}

// ----------------------- Delete Tour Package -----------------------

else if (isset($_GET["tour_id"])) {
  $id =  $_GET["tour_id"];
  $sql = "DELETE FROM tour_packages WHERE tour_id= $id";
  $delete_result = mysqli_query($con, $sql);
  if ($delete_result) {
    echo 1;
} else {
    $error_message = mysqli_error($con);
    // Check if the error message contains keywords related to foreign key constraints
    if (strpos($error_message, "foreign key constraint") !== false) {
        echo "Tour package has been booked by user so can't deleted.";
    } else {
        echo "An error occurred while deleting the record: $error_message";
    }
}
}

// ----------------------- Create Blog -----------------------

else if (isset($_POST['blog_save'])) {
  try {
    $imageName = '';
    $targetDirectory = "./uploads/blogs/";
    if (!file_exists($targetDirectory)) {
      mkdir($targetDirectory, 0777, true);
    }
    $newFileName = uniqid() . "_" . basename($_FILES["image"]["name"]); // Generate a unique name
    $targetFile = $targetDirectory . $newFileName;

    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // File type checking
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
      redirectTo("blogs.php", "Only JPG, JPEG, PNG, and GIF files are allowed.", true);
      exit;
    } else {
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imageName = $newFileName;
      } else {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Error uploading file.";
      }
    }


    if (
      (isset($_POST['title']) && empty($_POST['title'])) ||
      (isset($_POST['description']) && empty($_POST['description'])) ||
      (isset($_POST['category']) && empty($_POST['category']))
    ) {
      redirectTo("blogs.php", "All fields are required.", true);
      exit;
    }

    $title = $_POST["title"];
    $description = $_POST["description"];
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $description = str_replace("'", "''", $description);
    $title = str_replace("'", "''", $title);

    $sql = "INSERT INTO blogs (title, description,user_id,image,category) VALUES ('$title', '$description',$user_id,'$imageName','$category')";
    $result = mysqli_query($con, $sql);

    if ($result) {
      redirectTo("blogs.php", "Blog created successfully", false);
    }
  } catch (PDOException $e) {
    $_SESSION['success'] = false;
    $_SESSION['message'] =  "Error: " . $e->getMessage();
  }
}

// ----------------------- Update Blog -----------------------

else if (isset($_POST["updt_blog"])) {

  $imageName = $_POST['old_image'];
  $oldImage = $_POST['old_image'];
  $targetDirectory = "./uploads/blogs/";
  $newFileName = uniqid() . "_" . basename($_FILES["image"]["name"]); // Generate a unique name
  $targetFile = $targetDirectory . $newFileName;

  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // File type checking
  $allowedFormats = array("jpg", "jpeg", "png", "gif");
  if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
    redirectTo("blogs.php", "Only JPG, JPEG, PNG, and GIF files are allowed.", true);
    exit;
  }

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    $imageName = $newFileName;
    unlink("./uploads/blogs/" . $oldImage);
  } else {
    redirectTo("blogs.php", "Error while uploading blog", true);
  }

  $blog_id = $_POST["blog_id"];
  $title = $_POST["title"];
  $category = $_POST["category"];
  $description = $_POST["description"];
  $description = str_replace("'", "''", $description);
  $title = str_replace("'", "''", $title);
  // update blog query
  $update_blog = "UPDATE blogs SET image='$imageName',title='$title',category='$category', description='$description' WHERE blog_id=$blog_id";
  $update_result = mysqli_query($con, $update_blog);
  if ($update_result) {
    redirectTo("blogs.php", "Blog updated successfully.", false);
    exit;
  } else {
    redirectTo("blogs.php", mysqli_error($con), true);
  }
}

// ----------------------- Change status of blog -----------------------

else if (isset($_POST['blog_id']) && isset($_POST['status_change']) && $_POST['status_change'] == true) {
  $blog_id = $_POST['blog_id'];
  $selectBlog = "select * from blogs where blog_id = $blog_id";
  $result = mysqli_query($con, $selectBlog);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $updatedStatus = $row['status'] == 0 ? 1 : 0;
  }
  $updateStatus = "update blogs set status='$updatedStatus' where blog_id='$blog_id'";

  echo  mysqli_query($con, $updateStatus);;
}

// ----------------------- Delete blog -----------------------
else if (isset($_POST["blog_id"]) && isset($_POST['delete_blog']) && $_POST['delete_blog'] == true) {
  $id = $_POST["blog_id"];
  $selectBlog = "select * from blogs where blog_id=$id";
  $result = mysqli_query($con, $selectBlog);
  $row = mysqli_fetch_array($result);
  if ($row['image'] != "") {
    unlink("./uploads/blogs/" . $row['image']);
  }
  $sql = "DELETE FROM blogs WHERE blog_id= $id";
  echo mysqli_query($con, $sql);
}

// ----------------------- Delete Booking -----------------------

else if (isset($_POST["booking_id"]) && isset($_POST['delete_booking']) && $_POST['delete_booking'] == true) {
  $id = $_POST["booking_id"];
  $sql = "DELETE FROM packageBooking WHERE id= $id";
  $delete_result =  mysqli_query($con, $sql);
  if ($delete_result) {
    echo 1;
  } else {
    $error_message = mysqli_error($con);
    if (strpos($error_message, "foreign key constraint") !== false) {
      echo "Booking has added in tour package so can't deleted.";
    } else {
      echo "An error occurred while deleting the record: $error_message";
    }
  }
}

// ----------------------- Cancel Booking -----------------------
else if (isset($_POST["cancelBooking"])) {
  $booking_id = $_POST["booking_id"];
  $remarks = $_POST["remarks"];
  $date = date("Y-m-d H:i:s");
  $role = $_SESSION['role'];
  $stateQuery = "update packageBooking set status = 0,cancel_at='$date',cancel_by=$role,cancel_remarks='$remarks' where id = $booking_id";
  $result = mysqli_query($con, $stateQuery);
  if ($result) {
    // Return a JSON response indicating success
    echo json_encode(array("success" => true, "message" => "Booking cancelled successfully"));
  } else {
    // Return a JSON response indicating failure
    echo json_encode(array("success" => false, "message" => "Error: " . mysqli_error($con)));
  }
}

// ----------------------- Contact -----------------------

else if (isset($_POST['contact'])) {
  $name = $_POST['name'];
  $mobile = $_POST['mobile'];
  $message = $_POST['message'];
  $email = $_POST['email'];
  $q5 = "insert into enquiries(`name`,`mobile`,`message`,`email`) values ('$name','$mobile','$message','$email');";
  $result = $con->query($q5);
  if ($result) {
    echo "success";
  }
}

// ----------------------- Delete Enquiries -----------------------

else if (isset($_POST["enquiry_id"]) && isset($_POST['delete_enquiry']) && $_POST['delete_enquiry'] == true) {
  $id = $_POST["enquiry_id"];
  $sql = "DELETE FROM tblcontact WHERE id= $id";
  echo mysqli_query($con, $sql);
}

// ----------------------- Create Question -----------------------

else if (isset($_POST["btn_question"])) {
  $name = $_POST["name"];
  $questionQuery = "insert into squestions (`name`) values ('$name')";
  $result = mysqli_query($con, $questionQuery);
  redirectTo("security-questions.php", "Question created successfully", false);
}

// ----------------------- Edit Question -----------------------

else if (isset($_POST["btn_edit_question"])) {
  $name = $_POST["name"];
  $id = $_POST["id"];
  $questionQuery = "update squestions set name = '$name' where id = $id";
  $result = mysqli_query($con, $questionQuery);
  redirectTo("security-questions.php", "Question created successfully", false);
}

// ----------------------- Delete Question -----------------------

else if (isset($_POST["question_id"]) && isset($_POST['delete_question']) && $_POST['delete_question'] == true) {
  $id = $_POST["question_id"];

  $sql = "DELETE FROM squestions WHERE id = $id";
  $delete_result = mysqli_query($con, $sql);

  if ($delete_result) {
    echo 1;
  } else {
    $error_message = mysqli_error($con);

    // Check if the error message contains keywords related to foreign key constraints
    if (strpos($error_message, "foreign key constraint") !== false) {
      echo "Someone has selected this question as a security question.";
    } else {
      echo "An error occurred while deleting the record: $error_message";
    }
  }
}

// ----------------------- Update Privacy Policy -----------------------

else if (isset($_POST["btn_privacy_policy"])) {
  $privacy_policy = $_POST["privacy_policy"];
  $date = date("Y-m-d H:i:s");
  $privacy_policy = str_replace("'", "''", $privacy_policy);
  $privacyQuery = "update settings set privacy_policy = '$privacy_policy',p_date = '$date'";
  $result = mysqli_query($con, $privacyQuery);
  redirectTo("settings.php", "Privacy Policy updated successfully", false);
}

// ----------------------- Update Terms And Condition -----------------------

else if (isset($_POST["btn_terms_condition"])) {
  $terms_condition = $_POST["terms_condition"];
  $date = date("Y-m-d H:i:s");
  $terms_condition = str_replace("'", "''", $terms_condition);
  $TermsQuery = "update settings set terms_condition = '$terms_condition',t_date = '$date'";
  $result = mysqli_query($con, $TermsQuery);
  redirectTo("settings.php", "Terms And Conditions updated successfully", false);
}

// ----------------------- Add Country -----------------------

else if (isset($_POST["country_save"])) {
  $name = $_POST["country_name"];
  $addCountryQuery = "insert into countries (name) values ('$name')";
  $result = mysqli_query($con, $addCountryQuery);
  if ($result) {
    redirectTo("countries.php", "Country added successfully", false);
  }
}

// ----------------------- Update Country -----------------------

else if (isset($_POST["country_edit"])) {
  $country_id = $_POST["country_id"];
  $name = $_POST["country_name"];
  $countryQuery = "update countries set name = '$name' where id = $country_id";
  $result = mysqli_query($con, $countryQuery);
  redirectTo("countries.php", "Country updated successfully", false);
}

// ----------------------- Delete Country -----------------------
else if (isset($_POST["country_id"]) && isset($_POST['country_delete']) && $_POST['country_delete'] == true) {
  $id =  $_POST["country_id"];
  $sql = "DELETE FROM countries WHERE id= $id";
  $delete_result = mysqli_query($con, $sql);

  if ($delete_result) {
    echo 1;
  } else {
    $error_message = mysqli_error($con);
    if (strpos($error_message, "foreign key constraint") !== false) {
      echo "Country has added in tour package so can't deleted.";
    } else {
      echo "An error occurred while deleting the record: $error_message";
    }
  }
}

// ----------------------- Add State -----------------------

else if (isset($_POST["state_save"])) {
  $name = $_POST["state_name"];
  $addCountryQuery = "insert into states (name) values ('$name')";
  $result = mysqli_query($con, $addCountryQuery);
  if ($result) {
    redirectTo("states.php", "State added successfully", false);
  }
}

// ----------------------- Update State -----------------------

else if (isset($_POST["state_edit"])) {
  if (empty($_POST['state_name'])) {
    redirectTo("states.php", "Please enter name", true);
    exit;
  }
  $state_id = $_POST["state_id"];
  $state_name = $_POST["state_name"];
  $stateQuery = "update states set name = '$state_name' where id = $state_id";
  $result = mysqli_query($con, $stateQuery);
  redirectTo("states.php", "State updated successfully", false);
}

// ----------------------- Delete State -----------------------
else if (isset($_POST["state_id"]) && isset($_POST['state_delete']) && $_POST['state_delete'] == true) {
  $id =  $_POST["state_id"];
  $sql = "DELETE FROM states WHERE id= $id";
  $delete_result = mysqli_query($con, $sql);

  if ($delete_result) {
    echo 1;
  } else {
    $error_message = mysqli_error($con);
    if (strpos($error_message, "foreign key constraint") !== false) {
      echo "State has added in tour package so can't deleted.";
    } else {
      echo "An error occurred while deleting the record: $error_message";
    }
  }
}

// ----------------------- Logout -----------------------

else if (isset($_POST['logout'])) {
  session_destroy();
  setcookie('user', '', time() - 3600, '/');
  echo "success";
  exit;
}

// ----------------------- Payment -----------------------
else if (isset($_POST["payment"])) {
  require_once 'config.php';
  require_once 'Transaction.php';

  $productPrice = $_POST['package_price']; // Calculate the total price as per your logic.
  $total_person = $_POST['total_person'];
  $user_email = $_SESSION['email'];
  $payment_method = $_POST['payment_method']; // Added to determine the payment method.

  if ($payment_method === 'manual') {
    // Manual payment method selected, store details in a table.
    // You can customize this part based on your table structure.

    $transaction = new Transaction();
    $data = $_POST;
    $data['total_price'] = $productPrice;
    $data['package_id'] = $_POST['package_id'];
    $data['total_person'] = $total_person;
    $data['message'] = $_POST['message'];
    $user_id = $_SESSION['user_id'];
    $payment_type = 0;
    $user = $transaction->insertTransaction($con, $_POST, $user_id, $payment_type);

    // Redirect or display a success message to the user.
    $_SESSION['success'] = true;
    $_SESSION['message'] = "You successfully booked a tour package. Go to dashboard for show more details.";
    header('Location: /packages.php');

  }
  // Stripe Payment Method
  elseif ($payment_method === 'strp') {
    require_once "StripeHelper.php";

    $productPrice = $_POST['package_price'];
    $total_person = $_POST['total_person'];
    $user_email = $_SESSION['email'];
    /**
     * When registration form submitted
     */
    if (isset($_POST['payment'])) {
      $transaction = new Transaction();
      $data = $_POST;
      $data['total_price'] = $productPrice;
      $data['package_id'] = $_POST['package_id'];
      $data['total_person'] = $total_person;
      $data['message'] = $_POST['message'];
      $user_id = $_SESSION['user_id'];
      $payment_type = 1;
      $user = $transaction->insertTransaction($con, $_POST, $user_id, $payment_type);
    }

    $name = $_POST['package_name'];

    $stripeHelper = new StripeHelper();
    $stripe = $stripeHelper->stripeClient;
    /**
     * Create product
     */
    $product = $stripeHelper->createProducts($name);
    /**
     * Create price for product
     */
    $pricee = $stripeHelper->createProductPrice($product, $productPrice);
    /**
     * create checkout session and payment link
     */
    $stripeSession = $stripe->checkout->sessions->create(
      array(
        'success_url' => $appUrl . 'success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $appUrl . 'packages.php',
        'payment_method_types' => array('card'),
        'mode' => 'payment',
        'line_items' => array(
          array(
            'price' => $pricee->id,
            'quantity' => $total_person,
          )
        ),
        "customer_email" => $user_email,
      )
    );
    header("Location: " . $stripeSession->url);
  }else{
    $_SESSION['success'] = false;
    $_SESSION['message'] = mysqli_error($con);
    header('Location: /packages.php');
  }
}

?>