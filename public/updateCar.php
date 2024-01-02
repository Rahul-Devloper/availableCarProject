<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
$errorMsg = '';
$userId = '';
session_start();
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
    $firstName = $_SESSION['firstName'];
    $email = $_SESSION['email'];
    $userId = $_SESSION['userId'];
}
// Check if 'id' parameter exists in the URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $car_id = intval($_GET['id']);

    // Perform the SQL query to fetch the user with the specified ID
    $sql = "SELECT * FROM cars WHERE car_id = $car_id";
    $result = $conn->query($sql);

    // Check if a user with the specified ID exists
    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        // echo 'Car not found.';
        // display error message
        echo '<script>alert("Car not found.");</script>';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updated_availability'])) {
    $car_id = intval($_GET['id']);
    // echo $car_id;
    // echo "is is posting here";
    $availabilityData = [];
    $days = $_POST['car_availability_day'];
    $startTimes = $_POST['availability_start_time'];
    $endTimes = $_POST['availability_end_time'];

    foreach ($days as $index => $day) {

        if ($day && $startTimes[$index] && $endTimes[$index]) {
            // Format the time range
            $timeRange = $startTimes[$index] . '-' . $endTimes[$index];

            if (!isset($availabilityData[$day])) {
                $availabilityData[$day] = [];
            }
            // Push the time range to the array for the corresponding day
            $availabilityData[$day][] = $timeRange;
        }
    }

    $jsonAvailabilityData = json_encode($availabilityData);

    $sql = "UPDATE cars SET availability_schedule = '$jsonAvailabilityData', is_available = false, car_availability_type = 'date_time' WHERE car_id = $car_id";
    $result = $conn->query($sql);

    //refresh page after update
    header("Location: ../public/updateCar.php?id=$car_id");
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_update'])) {

    $car_id = intval($_GET['id']);
    $car_make = $_POST['car_make'];
    $car_model = $_POST['car_model'];
    $car_type = $_POST['car_type'];
    // $car_availability_type = $_POST['car_availability_type'];
    $always_available = isset($_POST['always_available']) ? $_POST['always_available'] : false;
    $car_address = trim(htmlspecialchars($_POST['car_address']));
    $car_registration = $_POST['car_registration'];
    $price = $_POST['price'];
    $postal_code = $_POST['postal_code'];
    $car_image_name = '';
    $sql = '';


    function validateUKPostalCode($postcode) {
        // UK Postal Code pattern
        $postcodePattern = '/^[A-Z]{1,2}[0-9R][0-9A-Z]? [0-9][A-Z]{2}$/i';
        return preg_match($postcodePattern, $postcode);
    }
    
    // Function to validate UK Car Registration
    function validateUKCarRegistration($registration) {
        // UK Car Registration pattern
        $registrationPattern = '/^[A-Z]{2}\d{2} [A-Z]{3}$/i';
        return preg_match($registrationPattern, $registration);
    }

    if (!validateUKPostalCode($postal_code)) {
        $errorMsg = "Invalid Postal Code. Please enter a valid UK Postal Code.";
    }

    // Validate Car Registration
    if (!validateUKCarRegistration($car_registration)) {
        $errorMsg = "Invalid Car Registration. Please enter a valid UK Car Registration.";
    }
   

    if ($always_available == 'on') {
        $sql = "UPDATE cars SET car_make = '$car_make', car_model = '$car_model', car_type = '$car_type', car_availability_type = 'always', address_to_pickup = '$car_address', car_registration = '$car_registration', price = '$price', postal_code = '$postal_code', availability_schedule = NULL, is_available = true  WHERE car_id = $car_id";
    } else {
        $sql = "UPDATE cars  SET car_make = '$car_make', car_model = '$car_model', car_type = '$car_type', address_to_pickup = '$car_address', car_registration = '$car_registration', price = '$price', postal_code = '$postal_code'  WHERE car_id = $car_id";
    }



    // $result = $conn->query($sql);

    if ($conn->query($sql) === TRUE) {
        $lastInsertedCarId = $car_id;
        // echo $lastInsertedCarId;

        // Fetch latitude and longitude from Google Maps Geocoding API
        $address = $car_address; // Use the address from the form
        $apiKey = "AIzaSyCiIKcQ1Gdk6vO8ARVez1nOlXuMhXI2Mcw"; // Replace with your actual Google API Key
    
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        // Extract latitude and longitude from the response
        $latitude = $data['results'][0]['geometry']['location']['lat'];
        $longitude = $data['results'][0]['geometry']['location']['lng'];
    
        // Insert latitude and longitude into the car_geo_location table
        $sqlGeoLocation = "UPDATE car_geo_location SET latitude = '$latitude', longitude = '$longitude' WHERE car_id = $lastInsertedCarId";
        
        if ($conn->query($sqlGeoLocation) === TRUE) {
            // echo "Geolocation details inserted successfully";
            // display success message
            echo '<script>alert("Car details updated successfully.");</script>';
            header("Location: ../public/cars.php");
        } else {
            // echo "Error inserting geolocation details: " . $conn->error;
            // display an alert message
            echo '<script>alert("Error inserting geolocation details: ' . $conn->error . '");</script>';
            
        }
    
    }

    // //refresh page after update
    header("Location: ../public/updateCar.php?id=$car_id");
} else if (isset($_FILES["car_image"]['name']) && isset($_FILES["car_image"]["tmp_name"]) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['car_image_update'])) {
    $car_id = intval($_GET['id']);

    // delete old image from directory
    $delteImageSql = "SELECT car_image_name FROM cars WHERE car_id = $car_id";
    $result = $conn->query($delteImageSql);
    $row = $result->fetch_assoc();
    $oldImageName = $row['car_image_name'];
    

    $target_dir = "assets/img/uploads/";
    $car_image_name = $userId . "_" . basename($_FILES["car_image"]["name"]);
    $target_file = $target_dir . $userId . "_" . basename($_FILES["car_image"]["name"]);
    // echo $target_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["car_image"]["tmp_name"]);
    if ($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        // echo "File is not an image.";
        // display an alert message
        echo '<script>alert("File is not an image.");</script>';
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        // display an alert message
        echo '<script>alert("Sorry, file already exists.");</script>';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["car_image"]["size"] > 50000000) {
        // echo "Sorry, your file is too large.";
        // display an alert message
        echo '<script>alert("Sorry, your file is too large.");</script>';
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        // display an alert message
        echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");</script>';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // echo "Sorry, your file was not uploaded.";
        // display an alert message
        echo '<script>alert("Sorry, your file was not uploaded.");</script>';
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            // echo "The file " . htmlspecialchars(basename($_FILES["car_image"]["name"])) . " has been uploaded.";
            // display an alert message
            echo '<script>alert("The file ' . htmlspecialchars(basename($_FILES["car_image"]["name"])) . ' has been uploaded.");</script>';
            
        } else {
            // echo "Sorry, there was an error uploading your file.";
            // display an alert message
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
        }
    }

    $sql = "UPDATE cars SET car_image_name = '$car_image_name' WHERE car_id = $car_id";
    $result = $conn->query($sql);

    // delete old image from directory
    unlink("assets/img/uploads/" . $oldImageName);

    //refresh page after update
    header("Location: ../public/updateCar.php?id=$car_id");
}

else {
    // echo 'Invalid request. Car ID not provided.';
    // display an alert message
    echo '<script>alert("Invalid request. Car ID not provided.");</script>';
}

// Updating the modal availability data



// Close the MySQL connection
$conn->close();
?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/ownerSidebar.php'; ?>

<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- update car start -->
<section class="container-fluid">
    <div class="my-5">
        <h3 class="display-4 text-center">Update Your Car</h3>
    </div>

    <div class="card mb-3 mx-auto" style="max-width: 90%;">
        <div class="row g-0">
            <div class="col-md-5 d-flex justify-content-center align-items-center">
                <img src="../public/assets/img/uploads/<?php echo $car['car_image_name']; ?>" class="img-fluid rounded mr-2" alt="car_image">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <form action="updateCar.php?id=<?php echo $car['car_id']; ?>" method="POST" enctype="multipart/form-data" class="mx-auto text-color" style="width: 70%;">
                        <!-- show errorMsg if any -->
                        <?php if ($errorMsg) echo "<div class='red-text'>$errorMsg</div>"; ?>
                        <!-- Car details input fields -->
                        <div class="row text-muted" style="color: white;">
                            <div class="col-md-6 mb-3">
                                <label for="carMake">Car Make:</label>
                                <input name="car_make" type="text" class="form-control" id="carMake" placeholder="Toyota" value="<?php echo $car['car_make']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="carModel">Car Model:</label>
                                <input name="car_model" type="text" class="form-control" id="carModel" placeholder="Camry" value="<?php echo $car['car_model']; ?>" required>
                            </div>
                        </div>

                        <div class="row text-muted mb-3" style="color: white;">
                            <div class="col-md-6">
                                <label for="carRegistration">Car Registration:</label>
                                <input name="car_registration" type="text" class="form-control" id="carRegistration" placeholder="ABC-1234" value="<?php echo $car['car_registration']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="carType">Car Type:</label>
                                <input name="car_type" type="text" class="form-control" id="carType" placeholder="SUV, Sedan" value="<?php echo $car['car_type']; ?>" required>
                            </div>
                        </div>

                        <div class="row text-muted mb-3" style="color: white;">
                            
                            <div class="col-md-6">
                                <label for="price">Price Per Hour:</label>
                                <input name="price" type="text" class="form-control" id="carPrice" placeholder="30" value="<?php echo $car['price']; ?>" required>
                            </div>
                            <div class="col-md-6">
                            <label for="postalCode">Postal Code:</label>
                                <input name="postal_code" type="text" class="form-control" id="postalCode" placeholder="AB1 2CD" value="<?php echo $car['postal_code']; ?>" required>
                            </div>
                            <div class="col-md-6 btn-modification mt-3">

                                    <button type="button" class="btn btn-rounded" data-bs-toggle="modal" data-bs-target="#imageModal" >
                                        Upload New Image
                                    </button>
                                </div>

                            <div class="col-md-12 mt-3">
                                <?php if ($car['car_availability_type'] == 'date_time' && $car['is_available'] == 0) {
                                    $availabilitySchedule = $car['availability_schedule'];

                                    $scheduleArray = json_decode($availabilitySchedule, true);

                                    foreach ($scheduleArray as $day => $times) {
                                        echo '<span class="badge rounded-pill text-bg-primary me-2" style="background-color: #04d4f0!important;">' . ucfirst($day) . ': ';

                                        foreach ($times as $time) {
                                            list($startTime, $endTime) = explode('-', $time);
                                            echo $startTime . ' - ' . $endTime;
                                        }

                                        echo '</span>';
                                    }
                                }
                                ?>
                            </div>

                            <?php if ($car['car_availability_type'] == 'date_time' && $car['is_available'] == 0) { ?>
                                <div class="col-md-12 mt-3 btn-modification">
                                    <input type="checkbox" name="always_available" id="alwaysAvailable">
                                    <label for="alwaysAvailable">Change to Always Available</label>

                                    <button type="button" class="btn btn-rounded" data-bs-toggle="modal" data-bs-target="#availabilityModal" id="availabilityModalButton">
                                        Change Availability Schedule
                                    </button>
                                </div>

                            <?php } ?>

                            <?php if ($car['car_availability_type'] == 'always' && $car['is_available'] == 1) { ?>
                                <div class="col-md-12 mt-3">

                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#availabilityModal" id="availabilityModalButton">
                                        Create Availability Schedule
                                    </button>
                                </div>

                            <?php } ?>

                        </div>
                        <div class="row text-muted mb-3" style="color: white;">
                            <div class="col-md-12">
                                <label for="carColor">Address to Pickup:</label>
                                <textarea name="car_address" class="form-control" id="address" rows="3" required>
                                    <?php echo $car['address_to_pickup']; ?>
                                </textarea>
                            </div>
                        </div>

                        



                        <!-- Submit button to add a car -->
                        <div class="col-12 btn-modification">
                            <button type="submit" name="car_update" class="btn btn-rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- update car end -->

<!-- Modal -->
<div class="modal fade" id="availabilityModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update/ Create Schedule</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="updateCar.php?id=<?php echo $car['car_id']; ?>" method="POST">
                <div class="modal-body">
                    <div id="dateForm">
                        <div id="availabilityRows">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="day">Date:</label>
                                    <input name="car_availability_day[]" type="date" class="form-control" id="day">
                                </div>
                                <div class="col-md-4">
                                    <label for="startTime">Start Time:</label>
                                    <input name="availability_start_time[]" type="time" class="form-control" id="startTime">
                                </div>
                                <div class="col-md-4">
                                    <label for="endTime">End Time:</label>
                                    <input name="availability_end_time[]" type="time" class="form-control" id="endTime">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" onclick="addAvailabilityRow()">+</button>
                    </div>
                </div>
                <div class="modal-footer btn-modification">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="updated_availability" class="btn btn-rounded">Submit New Availability</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- image upload modal -->
<div class="modal fade" id="imageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update image</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="updateCar.php?id=<?php echo $car['car_id']; ?>" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <label for="image" class="form-label"> Upload Image</label>
                    <input class="form-control" type="file" id="image" name="car_image">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="car_image_update" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- include footer -->
<?php include '../app/components/footer.php'; ?>