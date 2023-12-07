<?php

include '../app/config/connection.php';

session_start();

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
    $sql = "SELECT * from cars where user_id = $userId and (car_make like '%$searchTerm%' or car_model like '%$searchTerm%')";
} else {
    $sql = "SELECT * from cars where user_id = $userId";
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
<?php include '../app/components/ownerSidebar.php'; ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<section class="my-5 container-fluid">
    <div class="btn-modification">
        <a class="btn" href="addCar.php" style="background-color: #059dc0!important; border: 1px solid #059DC0; color: white!important;">Add Car</a>
    </div>

    <!-- HTML form for search -->
    <form action="" method="GET">
        <label for="search">Search by Car Name or Car Model:</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
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
            echo '<img src="../public/assets/img/uploads/' . $car['car_image_name'] . '" class="card-img-top" alt="car-image" style="height: 11rem;">';
            echo '<div class="card-body">';
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            // echo '<small>Car Make</small>';
            echo '<h5 class="card-title cars-listing">' . htmlspecialchars($car['car_make']) . ' <i class="bx bxs-car""></i>' . '</h5>';

            echo '<p><small class="text-muted">Car Registration: </small>' . htmlspecialchars($car['car_registration']) . '</p>';
            echo '<p><small class="text-muted">Location: </small>' . htmlspecialchars($car['location']) . '</p>';
            echo '</div>';
            echo '<div class="col-lg-6">';
            echo '<p><small class="text-muted">Car Model: </small>' . htmlspecialchars($car['car_model']) . '</p>';
            echo '<p><small class="text-muted">Car Type: </small>' . htmlspecialchars($car['car_type']) . '</p>';
            echo '<p><small class="text-muted">Price: </small> £' . htmlspecialchars($car['price']) . '/hour</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="card-footer">';
            echo '<a href="updateCar.php?id=' . $car['car_id'] . '" class="btn btn-primary my-3" style = "margin-right: 5px;">Update</a>';
            echo '<a href="deleter.php?cars=' . $car['car_id'] . '" class="btn btn-danger my-3"  style = "margin-right: 5px;">Delete</a>';
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