<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access environment variables
$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];
$appUrl = $_ENV['APP_URL'];

$con = mysqli_connect($host, $user, $pass, $dbname);
if ($con === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


if ($con) {
    $showSetting = "SELECT * FROM settings";
    if ($result = mysqli_query($con, $showSetting)) {
        $row = mysqli_fetch_array($result);
        $_SESSION['favicon'] = $row['favicon'];
        $_SESSION['site_name'] = $row['site_name'];
        $_SESSION['logo'] = $row['logo'];
        $_SESSION['themeColor'] = $row['themeColor'];
        if (mysqli_num_rows($result) == 0) {
            mysqli_query($con, $seedSetting);
        }
    };
}

// $userTable = "CREATE TABLE IF NOT EXISTS users(
//     user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     username TEXT,
//     image TEXT,
//     email VARCHAR(100) UNIQUE,
//     password TEXT,
//     role int DEFAULT 0,
//     phone_no VARCHAR(50),
//     squestion int,
//     answer TEXT,
//     foreign key(squestion) REFERENCES squestions(id),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );";

// $seedUser = "INSERT INTO users (user_id,username,email,password,role,phone_no) VALUES (1,'Admin', 'admin@test.com', '$2y$10\$qEsMuZxv0gGo8YQSOv3zCu7cjga6KN1uikp7JfNbPPc8H1mY3tcPO',1,'98652301478')";

// $tourPackages = "CREATE TABLE IF NOT EXISTS tour_packages (
// 	tour_id int AUTO_INCREMENT Primary Key,
//     tour_name varchar(50),
//     description text,
//     images text default NULL,
//     price int,
//     country_name int,
//     state_name int,
//     other_details text,
//     foreign key(country_name) REFERENCES countries(id),
//     foreign key(state_name) REFERENCES states(id),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );
// ";

// $settings = "CREATE TABLE IF NOT EXISTS settings (
//     id int AUTO_INCREMENT primary key,
//     favicon VARCHAR(255),
//     logo VARCHAR(255),
//     site_name VARCHAR(255),
//     themeColor VARCHAR(50),
//     terms_condition text,
//     privacy_policy text,
//     t_date date,
//     p_date date
//   );
// ";

// $seedSetting = "INSERT INTO settings (favicon,logo,site_name,themeColor) VALUES ('favicon.png', 'logo.png', 'Tourism','#009ef7')";

// $blogTable = "CREATE TABLE IF NOT EXISTS blogs (
// 	blog_id int AUTO_INCREMENT Primary Key,
//     title varchar(50),
//     category varchar(255),
//     description text,
//     image text,
//     status Boolean default false,
//     user_id int,
//     FOREIGN key(user_id) REFERENCES users(user_id),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );
// ";


// $packageBooking = "CREATE TABLE IF NOT EXISTS packageBooking (
// 	id int AUTO_INCREMENT Primary Key,
//     booking_id varchar(20),
//     package_id int,
//     total_person int,
//     message text,
//     user_id int,
//     status int,
//     cancel_at date,
//     cancel_by int,
//     cancel_remarks text,
//     transaction_id int,
//     FOREIGN key(package_id) REFERENCES tour_packages(tour_id),
//     FOREIGN key(transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
//     FOREIGN key(user_id) REFERENCES users(user_id),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );
// ";

// $questions = "CREATE TABLE IF NOT EXISTS squestions (
// 	id int AUTO_INCREMENT Primary Key,
//     name varchar(50),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// );
// ";

// $contact = "CREATE TABLE IF NOT EXISTS enquiries (
// 	id int AUTO_INCREMENT Primary Key,
//     name varchar(50),
//     email varchar(50),
//     mobile text,
//     message text,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );
// ";

// $transaction = "CREATE TABLE IF NOT EXISTS `transactions` (
//     `id` int AUTO_INCREMENT NOT NULL primary key,
//     `amount` varchar(50) DEFAULT NULL,
//     `payment_status` varchar(50) DEFAULT NULL,
//     `payment_intent` varchar(50) DEFAULT NULL,
//     `payment_type` int DEFAULT NULL,
//     `user_id` int DEFAULT NULL,
//     `booking_id` int DEFAULT NULL,
//      FOREIGN key(user_id) REFERENCES users(user_id),
//     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//   )";

// $alterTransactions = "alter table transactions add FOREIGN key(booking_id) REFERENCES packageBooking(id) on delete cascade";



// if ($con) {
//     $checkCitiesTable = $con->query("SHOW TABLES LIKE 'cities'");
//     if ($checkCitiesTable->num_rows == 0) {
//         $cities = file_get_contents('./Database/cities.sql');
//         // The 'cities' table does not exist, create it
//         if ($con->multi_query($cities)) {
//             do {
//                 // Process the results for the current query
//                 if ($result = $con->store_result()) {
//                     // Process the result set here (e.g., fetch and handle data)
//                     $result->free();
//                 }
//             } while ($con->more_results() && $con->next_result());
//         } else {
//             // Handle error for creating 'cities' table
//             echo "Error creating cities table: " . $con->error;
//         }
//     } else {
//         // The 'cities' table already exists, skip the creation
//     }
// }

// if ($con) {
//     $checkCitiesTable = $con->query("SHOW TABLES LIKE 'states'");
//     if ($checkCitiesTable->num_rows == 0) {
//         $states = file_get_contents('Database/states.sql');
//         // The 'states' table does not exist, create it
//         if ($con->multi_query($states)) {
//             do {
//                 // Process the results for the current query
//                 if ($result = $con->store_result()) {
//                     // Process the result set here (e.g., fetch and handle data)
//                     $result->free();
//                 }
//             } while ($con->more_results() && $con->next_result());
//         } else {
//             // Handle error for creating 'cities' table
//             echo "Error creating cities table: " . $con->error;
//         }
//     } else {
//         // The 'cities' table already exists, skip the creation
//     }
// }

// if ($con) {
//     $checkCitiesTable = $con->query("SHOW TABLES LIKE 'countries'");
//     if ($checkCitiesTable->num_rows == 0) {
//         $countries = file_get_contents('Database/countries.sql');
//         // The 'countries' table does not exist, create it
//         if ($con->multi_query($countries)) {
//             do {
//                 // Process the results for the current query
//                 if ($result = $con->store_result()) {
//                     // Process the result set here (e.g., fetch and handle data)
//                     $result->free();
//                 }
//             } while ($con->more_results() && $con->next_result());
//         } else {
//             // Handle error for creating 'cities' table
//             echo "Error creating cities table: " . $con->error;
//         }
//     } else {
//         // The 'cities' table already exists, skip the creation
//     }
// }


// if ($con) {
//     mysqli_query($con, $questions);
//     mysqli_query($con, $userTable);
//     mysqli_query($con, $tourPackages);
//     mysqli_query($con, $settings);
//     mysqli_query($con, $blogTable);
//     mysqli_query($con, $transaction);
//     mysqli_query($con, $packageBooking);
//     mysqli_query($con, $contact);
//     mysqli_query($con, $alterTransactions);
// }

// if ($con) {
//     $showUser = "SELECT * FROM users where user_id = 1";
//     if ($result = mysqli_query($con, $showUser)) {
//         if (mysqli_num_rows($result) == 0) {
//             mysqli_query($con, $seedUser);
//         }
//     };
// }
?>