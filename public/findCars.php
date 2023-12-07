<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
// Array to store matched car IDs
$matchedCarIds = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $postalCode = htmlspecialchars($_POST['location']);
    $startDateTime = $_POST['startDate'] . ' ' . $_POST['startTime'];
    $endDateTime = $_POST['endDate'] . ' ' . $_POST['endTime'];

    // Perform a database query to find available cars
    $sql = "SELECT * FROM cars WHERE postal_code = '$postalCode' AND is_available = false AND car_availability_type = 'date_time'";

    $result = $conn->query($sql);

    $availability = NULL;

    // Process the result set and display available cars
    while ($row = $result->fetch_assoc()) {
        // Display car details as needed
        // echo 'Car ID: ' . $row['car_id'] . '<br>';
        // echo 'Car Make: ' . $row['car_make'] . '<br>';
        // echo 'Availability' . $row['availability_schedule'] . '<br>';

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
                        // bootstrap toast with error message
                        
                        echo 'no second match found';
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
                    echo "Match found for end time!";
                    // Add additional logic if needed
                    break; 
                }
            }
        } else {
            echo "No availability for the specified date.";
        }

        // Add other details
        echo '<hr>';
    }

    // Query for cars with "always" availability type and availability_schedule is NULL
    $sqlAlwaysAvailable = "SELECT * FROM cars WHERE postal_code = '$postalCode' AND is_available = true AND car_availability_type = 'always' AND availability_schedule IS NULL";
    $resultAlwaysAvailable = $conn->query($sqlAlwaysAvailable);

    // Process the result set and add matched car IDs
    while ($rowAlwaysAvailable = $resultAlwaysAvailable->fetch_assoc()) {
        array_push($matchedCarIds, $rowAlwaysAvailable['car_id']);
    }

}

$conn->close();
?>


<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>


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
            <form method="post" action="findCars.php" class="box-design">
                <div class="form-group row mb-3">
                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter Postal Code" value="<?php echo isset($_POST['location']) ? $_POST['location'] : ''; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date1" class="col-sm-2 col-form-label">Date and Time</label>
                    <div class="col-sm-4">
                        <input type="date" name="startDate" class="form-control" id="date1" value="<?php echo isset($_POST['startDate']) ? $_POST['startDate'] : ''; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="startTime" class="form-control" id="time1" value="<?php echo isset($_POST['startTime']) ? $_POST['startTime'] : ''; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date2" class="col-sm-2 col-form-label">Date and Time</label>
                    <div class="col-sm-4">
                        <input type="date" name="endDate" class="form-control" id="date2" value="<?php echo isset($_POST['endDate']) ? $_POST['endDate'] : ''; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="endTime" class="form-control" id="time2" value="<?php echo isset($_POST['endTime']) ? $_POST['endTime'] : ''; ?>">
                    </div>
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

    <!-- Display available cars -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $matchedCarIds == []) {
            echo '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                <div class="toast-header">
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    No second match found.
                </div>
            </div>';
            echo '<script>
                var toast = new bootstrap.Toast(document.querySelector(".toast"));
                toast.show();
              </script>';
        } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $matchedCarIds != []) {
            // Loop over matched car IDs and display details in cards
            foreach ($matchedCarIds as $carId) {
                // Query to get details for each matched car
                $sqlCarDetails = "SELECT * FROM cars WHERE car_id = $carId";
                $resultCarDetails = $conn->query($sqlCarDetails);

                while ($rowCarDetails = $resultCarDetails->fetch_assoc()) {
                    echo '<div class="col">';
                    echo '<div class="card">';
                    echo '<img src="../public/assets/img/uploads/' . $rowCarDetails['car_image_name'] . '" class="card-img-top" alt="car-image" style="height: 11rem;">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title cars-listing" style="width: fit-content !important;">' . htmlspecialchars($rowCarDetails['car_make']) . ' <i class="bx bxs-car"></i>' . '</h5>';
                    echo '<p><small class="text-muted">Car Model: </small>' . htmlspecialchars($rowCarDetails['car_model']) . '</p>';
                    echo '<p><small class="text-muted">Car Registration: </small>' . htmlspecialchars($rowCarDetails['car_registration']) . '</p>';
                    echo '<p><small class="text-muted">Car Type: </small>' . htmlspecialchars($rowCarDetails['car_type']) . '</p>';
                    echo '<p><small class="text-muted">Location: </small>' . htmlspecialchars($rowCarDetails['address_to_pickup']) . '</p>';
                    echo '<p><small class="text-muted">Price: </small> £' . htmlspecialchars($rowCarDetails['price']) . '/hour</p>';
                    echo '</div>';
                    echo '<div class="card-footer btn-modification">';
                    echo '<a href="book.php?id=' . $rowCarDetails['car_id'] . '" class="btn btn-rounded mx-auto" style="margin-right: 5px;">Book</a>';
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