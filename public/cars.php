<?php

include '../app/config/connection.php';


$firstName = '';
$email = '';
$userId = '';
session_start();
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
    $firstName = $_SESSION['firstName'];
    $email = $_SESSION['email'];
    if (isset($_SESSION['adminSelectedUserId']) && $_SESSION['role'] == 'admin') {
        $userId = $_SESSION['adminSelectedUserId'];
    } else {
        $userId = $_SESSION['userId'];
    }
}



if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql = "SELECT cars.*, AVG(feedback.rating) as avg_rating 
            FROM cars 
            LEFT JOIN car_feedback feedback ON cars.car_id = feedback.car_id
            WHERE cars.user_id = $userId 
                AND (cars.car_make LIKE '%$searchTerm%' 
                    OR cars.car_model LIKE '%$searchTerm%' 
                    OR cars.car_registration LIKE '%$searchTerm%')
            GROUP BY cars.car_id";
} else {
    $sql = "SELECT cars.*, AVG(feedback.rating) as avg_rating 
            FROM cars 
            LEFT JOIN car_feedback feedback ON cars.car_id = feedback.car_id
            WHERE cars.user_id = $userId
            GROUP BY cars.car_id";
}
$result = $conn->query($sql);

// Initialize an empty array to store the results
$carsArray = [];

// Fetch all rows and store them in the array
while ($row = $result->fetch_assoc()) {
    $carsArray[] = [
        'car_id' => $row['car_id'],
        'car_make' => $row['car_make'],
        'car_model' => $row['car_model'],
        'car_type' => $row['car_type'],
        'created_at' => $row['created_at'],
        'car_registration' => $row['car_registration'],
        'location' => $row['address_to_pickup'],
        'user_id' => $row['user_id'],
        'car_image_name' => $row['car_image_name'],
        'price' => $row['price'],
        'avg_rating' => round($row['avg_rating'], 1),
    ];
}


// Close the MySQL connection
$conn->close();
?>


<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    switch ($role) {
        case 'owner':
            include '../app/components/ownerSidebar.php';
            break;

        case 'driver':
            include '../app/components/driverSidebar.php';
            break;

        case 'admin':
            include '../app/components/adminSidebar.php';
            break;

            // Add more cases if you have additional roles

        default:
            // Handle cases where the role doesn't match any of the expected values
            break;
    }
} ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<section class="my-5 container-fluid">
    <div class="btn-modification">
        <a class="btn btn-rounded mt-3" href="addCar.php" style="background-color: #059dc0!important; border: 1px solid #059DC0; color: white!important;">Add Car</a>
    </div>

    <!-- HTML form for search -->
    <form action="" method="GET">
        <label for="search">Search by Car Name or Car Model or Car Registration:</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <span class="btn-modification">
        <button class="btn btn-rounded" type="submit">Search</button>
        </span>
    </form>

    <?php
    if ($carsArray == []) {
        echo '        <h4 class="display-4">No cars found</h4>';
    } else {
        echo '<section class="my-5 px-3 container-fluid">';
        echo '<div class="row row-cols-1 row-cols-md-3 g-4">';

        foreach ($carsArray as $car) {
            echo '<div class="col">';
            echo '<div class="card">';
            echo '<img src="../public/assets/img/uploads/' . $car['car_image_name'] . '" class="card-img-top" alt="car-image" style="height: 15rem;">';
            echo '<div class="card-body">';
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            // echo '<small>Car Make</small>';
            echo '<h5 class="card-title cars-listing">' . htmlspecialchars($car['car_make']) . ' <i class="bx bxs-car""></i>' .  '</h5>';
            echo '<p><small class="text-muted">Car Registration: </small>' . htmlspecialchars($car['car_registration']) . '</p>';
            echo '<p><small class="text-muted">Location: </small>' . htmlspecialchars($car['location']) . '</p>';
            echo '</div>';
            echo '<div class="col-lg-6">';
            echo '<p><small class="text-muted">Car Model: </small>' . htmlspecialchars($car['car_model']) . '</p>';
            echo '<p><small class="text-muted">Car Type: </small>' . htmlspecialchars($car['car_type']) . '</p>';
            echo '<p><small class="text-muted">Price: </small> £' . htmlspecialchars($car['price']) . '/hour</p>';
            echo !empty($car['avg_rating']) ? '<p class="rating-view"><small>Average Rating: </small><i class="bi bi-star-fill"></i>' . $car['avg_rating'] . '</p>'  : '<p class="rating-view"><small>Average Rating: </small>No ratings </p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="card-footer d-flex justify-content-center">';
            echo '<a href="updateCar.php?id=' . $car['car_id'] . '" class="btn btn-rounded btn-primary my-3" style="margin-right: 10px; width: 30%;">Update</a>';
            echo '<a href="deleter.php?cars=' . $car['car_id'] . '" class="btn btn-rounded btn-danger my-3" style="margin-right: 10px; width: 30%;">Delete</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';

        echo '</section>';
    }
    ?>

</section>





<!-- footer -->
<?php include '../app/components/footer.php'; ?>