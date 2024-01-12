<?php
 include "config.php";
function queryToJSON($query) {
    include "config.php";
    $result = mysqli_query($con, $query);
    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    header("Content-Type: application/json");
    echo json_encode($data);
}

// ------------------------ Single user retrieved ------------------------
if (isset($_POST['user_id'])) {
    $query = "SELECT * FROM users where user_id = '" . $_POST["user_id"] . "'";
    queryToJSON($query);
}
// ------------------------ All users retrieved ------------------------
else if (isset($_GET['allUser'])) {
    try {
        $query = "SELECT * FROM users where user_id != 1 and role = 0;";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ All packages retrieved ------------------------
else if (isset($_GET['allPackage'])) {
    try {
        $query = "SELECT * FROM tour_packages ORDER BY created_at desc;";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ All blogs retrieved ------------------------
else if (isset($_GET['allBlogs'])) {
    try {
        $query = "SELECT * FROM blogs";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ All package booking retrieved ------------------------
else if (isset($_GET['allBooking'])) {
    try {
        $query = "SELECT p.*, t.tour_name, t.price,u.email FROM packageBooking as p, tour_packages as t,users as u WHERE p.user_id = u.user_id and p.package_id = t.tour_id ORDER BY p.created_at DESC";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ single package booking retrieved ------------------------
else if (isset($_POST['booking_id'])) {
    try {
        $booking_id = $_POST['booking_id'];
        $query = "SELECT p.*,t.tour_name,t.price,u.email,u.username,u.phone_no FROM packageBooking as p, tour_packages as t,users as u where p.user_id = u.user_id and id = $booking_id and p.package_id = t.tour_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ User wise package booking retrieved ------------------------
else if (isset($_GET['user_booking'])) {
    try {
        $user_id = $_GET['user_id'];
        $query = "SELECT p.*,t.tour_name,t.price FROM packageBooking as p, tour_packages as t where p.user_id = $user_id and p.package_id = t.tour_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ All Enquiries retrieved ------------------------
else if (isset($_GET['contact_us'])) {
    try {
        $query = "SELECT * from enquiries";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch single Contact us retrieved ------------------------
else if (isset($_POST['enquiry_id'])) {
    try {
        $enquiry_id = $_POST['enquiry_id'];
        $query = "SELECT * from enquiries where id = $enquiry_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch Managers retrieved ------------------------
else if (isset($_GET['allManagers'])) {
    try {
        $query = "SELECT * FROM users where user_id != 1 and role = 2;";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Manage search package flow ------------------------
else if (isset($_POST['search'])) {
    try {
        $countQuery = "SELECT COUNT(*) AS total FROM tour_packages AS tp";
        $countQuery .= " LEFT JOIN countries AS c ON tp.country_name = c.id";
        $countQuery .= " LEFT JOIN states AS s ON tp.state_name = s.id";
        $countQuery .= " WHERE 1";

        if (isset($_POST["packageName"])) {
            $search = mysqli_real_escape_string($con, $_POST["packageName"]);
            $countQuery .= " AND (tp.tour_name LIKE '%" . $search . "%'
                OR tp.price LIKE '%" . $search . "%')";
        }
        if (isset($_POST["country"])) {
            $selectedCountry = mysqli_real_escape_string($con, $_POST["country"]);
            if ($selectedCountry !== "all") {
                $countQuery .= " AND tp.country_name = '" . $selectedCountry . "'";
            }
        }

        $query = "SELECT tp.*, c.name as country_name, s.name as state_name FROM tour_packages AS tp";
        $query .= " LEFT JOIN countries AS c ON tp.country_name = c.id";
        $query .= " LEFT JOIN states AS s ON tp.state_name = s.id";
        $query .= " WHERE 1";

         if (isset($_POST["packageName"])) {
            $search = mysqli_real_escape_string($con, $_POST["packageName"]);
            $query .= " AND (tp.tour_name LIKE '%" . $search . "%'
                        OR tp.price LIKE '%" . $search . "%')";
        }
        if (isset($_POST["country"])) {
            $selectedCountry = mysqli_real_escape_string($con, $_POST["country"]);
            if ($selectedCountry !== "all") {
                $query .= " AND tp.country_name = '" . $selectedCountry . "'";
            }
        }

        if (isset($_POST["page"])) {
            $page = $_POST["page"];
        } else {
            $page = 1;
        }

        $limit = 12;

        $offset = ($page - 1) * $limit;
        $query .= " LIMIT $limit OFFSET $offset";
        $result = mysqli_query($con, $query);


        $data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        $countResult = mysqli_query($con, $countQuery);
        $totalRecords = mysqli_fetch_assoc($countResult)['total'];

        // Return both packages and totalRecords
        header("Content-Type: application/json");
        echo json_encode(array("packages" => $data, "totalRecords" => $totalRecords));
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch all questions retrieved ------------------------
else if (isset($_GET['allQuestion'])) {
    try {
        $query = "SELECT * FROM squestions";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch one question ------------------------
else if (isset($_POST['question_id'])) {
    $id = $_POST['question_id'];
    $query = "SELECT * FROM squestions where id = $id";
    queryToJSON($query);
}

// ------------------------ Fetch all countries ------------------------
else if (isset($_GET['allcountries'])) {
    try {
        $query = "SELECT * FROM countries";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch country wise states ------------------------
else if (isset($_GET['country_id'])) {
    try {
        $country_id = $_GET['country_id'];
        $query = "SELECT * FROM states where country_id = $country_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch All Countries ------------------------
else if (isset($_GET['allCountries'])) {
    try {
        $query = "SELECT * FROM countries";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch All Countries ------------------------
else if (isset($_GET['allStates'])) {
    try {
        $query = "SELECT * FROM states";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch single state retrieved ------------------------
else if (isset($_POST['state_id'])) {
    try {
        $id = $_POST['state_id'];
        $query = "SELECT * from states where id = $id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ Fetch single country retrieved ------------------------
else if (isset($_POST['country_id']) && isset($_POST['country_edit'])) {
    try {
        $id = $_POST['country_id'];
        $query = "SELECT * from countries where id = $id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ All Transactions retrieved ------------------------
else if (isset($_GET['transactions'])) {
    try {
        $query = "SELECT t.*,p.booking_id from transactions as t,packageBooking as p where t.id = p.transaction_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// ------------------------ single tour_package booking retrieved ------------------------
else {
    $query = "SELECT * FROM tour_packages where tour_id = '" . $_POST["tour_id"] . "'";
    queryToJSON($query);
}
?>