<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
// Array to store matched car IDs
$matchedCarIds = [];

session_start();
if ($_SESSION !== NULL) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'owner') {
        header("Location: 404.php");
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $postalCode = htmlspecialchars($_POST['location']);
    $startDateTime = $_POST['startDate'] . ' ' . $_POST['startTime'];
    $endDateTime = $_POST['endDate'] . ' ' . $_POST['endTime'];
    $_SESSION['startDateTime'] = $startDateTime;
    $_SESSION['endDateTime'] = $endDateTime;
    // Perform a database query to find available cars
    $sql = "SELECT * FROM cars WHERE postal_code = '$postalCode' AND is_available = false AND car_availability_type = 'date_time' AND booking_status = 'available'";

    $result = $conn->query($sql);

    $availability = NULL;

    // Process the result set and display available cars
    while ($row = $result->fetch_assoc()) {

        $availability = $row['availability_schedule'];


        $payloadStartDate = $_POST['startDate'];
        $payloadStartTime = $_POST['startTime'];
        $payloadEndDate = $_POST['endDate'];
        $payloadEndTime = $_POST['endTime'];

        $availabilitySchedule = $availability;

        // Convert the JSON string to a PHP associative array
        $availabilityArray = json_decode($availabilitySchedule, true);

        // Check if the payload date exists in the availability schedule
        if (isset($availabilityArray[$payloadStartDate])) {
            // Check if the payload start time is within any of the time ranges
            $payloadTime = strtotime($payloadStartTime);
            $payloadEndTime = strtotime($payloadEndTime);

            foreach ($availabilityArray[$payloadStartDate] as $timeRange) {
                list($startTime, $endTime) = explode('-', $timeRange);
                $startTimestamp = strtotime($startTime);
                $endTimestamp = strtotime($endTime);

                if ($payloadTime >= $startTimestamp && $payloadTime <= $endTimestamp) {
                    if ($payloadEndTime >= $startTimestamp && $payloadEndTime <= $endTimestamp) {
                        array_push($matchedCarIds, $row['car_id']);
                    } else {
                        
                        echo '<script>alert("No second match found");</script>';
                    }
                    break;
                }
            }

            // Check if the payload end time is within any of the time ranges
            foreach ($availabilityArray[$payloadStartDate] as $timeRange) {
                list($startTime, $endTime) = explode('-', $timeRange);
                $startTimestamp = strtotime($startTime);
                $endTimestamp = strtotime($endTime);

                if ($payloadEndTime >= $startTimestamp && $payloadEndTime <= $endTimestamp) {
                    // echo "Match found for end time!";
                    
                    break;
                }
            }
        } else {
            // echo "No availability for the specified date.";
            // display error message
            // echo '<script>alert("No availability for the specified date.");</script>';
        }

        // echo '<hr>';
    }

    // Query for cars with "always" availability type and availability_schedule is NULL
    $sqlAlwaysAvailable = "SELECT * FROM cars WHERE postal_code = '$postalCode' AND is_available = true AND car_availability_type = 'always' AND availability_schedule IS NULL AND booking_status = 'available'";
    $resultAlwaysAvailable = $conn->query($sqlAlwaysAvailable);

    // Process the result set and add matched car IDs
    while ($rowAlwaysAvailable = $resultAlwaysAvailable->fetch_assoc()) {
        array_push($matchedCarIds, $rowAlwaysAvailable['car_id']);
    }
}

?>


<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'driver') {
    include '../app/components/driverSidebar.php';
    include '../app/components/sidebarButton.php';
}

?>



<section class="container-fluid my-5">
    <h4 class="display-6 text-center">
        All you have to do is fill in the below form, and we will show you the available cars for you to choose from.
    </h4>

    <div class="row">
        <h4 class="display-6 text-center my-5">
            Rent Cars from your nearby Location!!!
        </h4>
        <div class="col-lg-6 mx-auto my-auto">
            <!-- find cars form -->
            <form method="post" action="findCars.php#carResults" class="box-design" onsubmit="return validateForm()">
                <div class="form-group row mb-3">
                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <!-- <input type="text" class="form-control" id="location" name="location" placeholder="Enter Postal Code" value="<?php echo isset($_POST['location']) ? $_POST['location'] : ''; ?>"> -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="location" name="location" placeholder="Enter Postal Code" value="<?php echo isset($_POST['location']) ? $_POST['location'] : ''; ?>" required>
                            <div class="input-group-append">
                                <i class='bx bx-current-location' style="font-size: xx-large; padding: 5px; background: black; color: white" onclick="getCurrentLocation()"></i>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="form-group row">
                    <label for="date1" class="col-sm-2 col-form-label">Start Date and Time</label>
                    <div class="col-sm-4">
                        <input type="date" name="startDate" class="form-control" id="date1" value="<?php echo isset($_POST['startDate']) ? $_POST['startDate'] : ''; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="startTime" class="form-control" id="time1" value="<?php echo isset($_POST['startTime']) ? $_POST['startTime'] : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date2" class="col-sm-2 col-form-label">End Date and Time</label>
                    <div class="col-sm-4">
                        <input type="date" name="endDate" class="form-control" id="date2" value="<?php echo isset($_POST['endDate']) ? $_POST['endDate'] : ''; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="endTime" class="form-control" id="time2" value="<?php echo isset($_POST['endTime']) ? $_POST['endTime'] : ''; ?>" required>
                    </div>
                </div>
                <div id="locationFormError" class="text-center ">
                    <!-- filled by javascript validation -->
                </div>

                <!-- Submit button to add a car -->
                <div class="col-12 btn-modification">
                    <button type="submit" name="find_car" class="btn btn-rounded">Find Car</button>
                </div>
            </form>
            <!-- find cars form end -->
        </div>
        <div class="col-lg-6">
            <img class="img-fluid" src="../public/assets/img/contact.png" alt="contactImage">
        </div>
    </div>
    <section>

        <section class="container-fluid my-3" id="carResults"></section>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $matchedCarIds == []) {
            echo '<h3 class="text-center display-3">Sorry!!! No cars found for this location</h3>';
        } ?>

        <!-- Display available cars -->
        <div class="row row-cols-1 row-cols-md-3 g-4 my-3">
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && $matchedCarIds != []) {
                // Loop over matched car IDs and display details in cards
                foreach ($matchedCarIds as $carId) {
                    // Query to get details for each matched car
                    $sqlCarDetails = "SELECT * FROM cars WHERE car_id = $carId";
                    $resultCarDetails = $conn->query($sqlCarDetails);

                    while ($rowCarDetails = $resultCarDetails->fetch_assoc()) {
                        echo '<div class="col">';
                        echo '<div class="card">';
                        echo '<img src="../public/assets/img/uploads/' . $rowCarDetails['car_image_name'] . '" class="card-img-top img-fluid" alt="car-image" style="height: 20rem;">';
                        echo '<div class="card-body">';
                        echo '<div class="row">';
                        echo '<div class="col-lg-6">';
                        echo '<h5 class="card-title cars-listing">' . htmlspecialchars($rowCarDetails['car_make']) . ' <i class="bx bxs-car""></i>' . '</h5>';

                        echo '<p><small class="text-muted">Car Registration: </small>' . htmlspecialchars($rowCarDetails['car_registration']) . '</p>';
                        echo '<p><small class="text-muted">Location: </small>' . htmlspecialchars($rowCarDetails['address_to_pickup']) . '</p>';
                        echo '</div>';
                        echo '<div class="col-lg-6">';
                        echo '<p><small class="text-muted">Car Model: </small>' . htmlspecialchars($rowCarDetails['car_model']) . '</p>';
                        echo '<p><small class="text-muted">Car Type: </small>' . htmlspecialchars($rowCarDetails['car_type']) . '</p>';
                        echo '<p><small class="text-muted">Price: </small> £' . htmlspecialchars($rowCarDetails['price']) . '/hour</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="card-footer btn-modification text-center">';
                        echo '<a href="viewCar.php?id=' . $rowCarDetails['car_id'] . '" class="btn btn-rounded mx-auto" style="margin-right: 5px;">View More</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            } else {
                echo '</br>';
            }
            ?>
        </div>
    </section>



    <!-- include footer -->
    <?php include '../app/components/footer.php'; ?>