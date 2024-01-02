<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<!-- check if user is logged in -->

<!-- get session values -->
<?php


$firstName = '';
$email = '';
$userId = '';
session_start();

// check if user is logged in
if ($_SESSION == NULL) {
    header("Location: signIn.php");
    exit();
}

if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
    $firstName = $_SESSION['firstName'];
    $email = $_SESSION['email'];
    // if session contains userId
    if (isset($_SESSION['adminSelectedUserId']) && $_SESSION['role'] == 'admin') {
        $userId = $_SESSION['adminSelectedUserId'];
    } else {
        $userId = $_SESSION['userId'];
    }
}

?>

<?php
$sql = "";
$errorMsg = "";
if (isset($_POST['add_car_submit']) && isset($_FILES['car_image']['name']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $car_make = $_POST['car_make'];
    $car_model = $_POST['car_model'];
    $car_type = $_POST['car_type'];
    $car_availability_type = $_POST['car_availability_type'];
    $car_address = trim($_POST['car_address']);
    $car_registration = $_POST['car_registration'];
    $price = $_POST['price'];
    $postal_code = $_POST['postal_code'];

    function validateUKPostalCode($postcode)
    {
        // UK Postal Code pattern
        $postcodePattern = '/^[A-Z]{1,2}[0-9R][0-9A-Z]? [0-9][A-Z]{2}$/i';
        return preg_match($postcodePattern, $postcode);
    }

    // ===image upload===
    $target_dir = "assets/img/uploads/";
    $car_image_name = $userId . "_" . basename($_FILES["car_image"]["name"]);
    $target_file = $target_dir . $userId . "_" . basename($_FILES["car_image"]["name"]);
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
        echo '<script>alert("File is not an image.")</script>';
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        // display an alert message
        echo '<script>alert("Sorry, file already exists.")</script>';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["car_image"]["size"] > 50000000) {
        // echo "Sorry, your file is too large.";
        // display an alert message
        echo '<script>alert("Sorry, your file is too large.")</script>';
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        // display an alert message
        echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.")</script>';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // echo "Sorry, your file was not uploaded.";
        // display an alert message
        echo '<script>alert("Sorry, your file was not uploaded.")</script>';
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            // echo "The file " . htmlspecialchars(basename($_FILES["car_image"]["name"])) . " has been uploaded.";
            // display an alert message
            echo '<script>alert("The file ' . htmlspecialchars(basename($_FILES["car_image"]["name"])) . ' has been uploaded.")</script>';
        } else {
            // echo "Sorry, there was an error uploading your file.";
            // display an alert message
            echo '<script>alert("Sorry, there was an error uploading your file.")</script>';
        }
    }




    if ($car_availability_type == 'always') {
        $sql = "INSERT INTO cars (user_id, car_make, car_model, car_registration, car_type, car_availability_type, address_to_pickup, availability_schedule, is_available, car_image_name, price, postal_code)
VALUES ( $userId, '$car_make', '$car_model', '$car_registration', '$car_type', '$car_availability_type', '$car_address', NULL, true, '$car_image_name', '$price', '$postal_code')";
    } else {
        // echo $userId;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            // print_r($jsonAvailabilityData);

            $sql = "INSERT INTO cars (user_id, car_make, car_model, car_registration, car_type, car_availability_type, address_to_pickup, availability_schedule, is_available, car_image_name, price, postal_code)
            VALUES ('$userId', '$car_make', '$car_model', '$car_registration', '$car_type', '$car_availability_type', '$car_address', '$jsonAvailabilityData', false, '$car_image_name', '$price', '$postal_code')";
        }


        //        
    }

    if ($conn->query($sql) === TRUE && $car_address != null) {
        // echo "New Car created successfully";
        $lastInsertedCarId = $conn->insert_id;

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
        $sqlGeoLocation = "INSERT INTO car_geo_location (car_id, latitude, longitude) VALUES ($lastInsertedCarId, $latitude, $longitude)";

        if ($conn->query($sqlGeoLocation) === TRUE) {
            // echo "Geolocation details inserted successfully";
            header("Location: ../public/cars.php");
        } else {
            // echo "Error inserting geolocation details: " . $conn->error;
            // display an alert message
            echo '<script>alert("Error inserting geolocation details: ' . $conn->error . '")</script>';
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else if (isset($_POST['add_car_submit']) && !isset($_FILES['car_image']['name']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $errorMsg = "Please ensure to fill all fields";
    // echo "Error: ENsure to fill all fields correctly " . "<br>";
}



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


        default:
            break;
    }
} ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<style>
    
</style>

<!-- The sidebar -->
<section id='content-wrapper' class="my-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="container content w-85">
                <div>
                    <!-- Add car form -->
                    <h3 class="text-center display-6">Add Your Car</h3>
                    <form action="addCar.php" method="POST" enctype="multipart/form-data" class="box-design mx-auto text-color" style="width: 70%;">
                        <!-- show errorMsg if any -->
                        <?php if ($errorMsg) echo "<p class='alert-style' style='background-color: #E53935; color: white; text-align: center;'>$errorMsg</p>"; ?>
                        <!-- Car details input fields -->
                        <div class="row" style="color: white;">
                            <div class="col-md-6 mb-3">
                                <label for="carMake">Car Make:</label>
                                <input name="car_make" type="text" class="form-control" id="carMake" placeholder="Toyota" required value="<?php if (isset($_POST['car_make'])) echo $_POST['car_make']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="carModel">Car Model:</label>
                                <input name="car_model" type="text" class="form-control" id="carModel" placeholder="Camry" required value="<?php if (isset($_POST['car_model'])) echo $_POST['car_model']; ?>">
                            </div>
                        </div>

                        <div class="row mb-3" style="color: white;">
                            <div class="col-md-6">
                                <label for="carRegistration">Car Registration:</label>
                                <input name="car_registration" type="text" class="form-control" id="carRegistration" placeholder="ABC-1234" required value="<?php if (isset($_POST['car_registration'])) echo $_POST['car_registration']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="carImage">Car Image: (PNG)</label>
                                <input name="car_image" type="file" class="form-control" id="carImage">
                            </div>
                        </div>

                        <div class="row mb-3" style="color: white;">
                            <div class="col-md-6">
                                <label for="carType">Car Type:</label>
                                <input name="car_type" type="text" class="form-control" id="carType" placeholder="SUV, Sedan" required value="<?php if (isset($_POST['car_type'])) echo $_POST['car_type']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="price">Price Per Hour:</label>
                                <input name="price" type="number" class="form-control" id="carPrice" placeholder="30" required value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="postalCode">Postal Code:</label>
                                <input name="postal_code" type="text" class="form-control" id="postalCode" placeholder="AB1 2CD" required value="<?php if (isset($_POST['postal_code'])) echo $_POST['postal_code']; ?>">
                            </div>
                            <!-- car availability dropdown with options specific day and time or always -->
                            <div class="col-md-6">
                                <label for="carAvailability">Car Availability Type:</label>
                                <select name="car_availability_type" class="form-select" id="carAvailability" aria-label="Default select example" onchange="toggleDateForm(this)">
                                    <option selected>Select</option>
                                    <option value="date_time">Date and Time</option>
                                    <option value="always">Always</option>
                                </select>
                            </div>

                            <!-- Date and Time Form -->
                            <div id="dateForm" style="display: none;">
                                <div id="availabilityRows">
                                    <div class="row mb-3" style="color: white;">
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
                        <div class="row mb-3" style="color: white;">
                            <div class="col-md-12">
                                <label for="carColor">Address to Pickup:</label>
                                <textarea name="car_address" class="form-control" id="address" rows="3" required></textarea>
                            </div>
                        </div>


                        <!-- Submit button to add a car -->
                        <div class="col-12 btn-modification">
                            <button type="submit" name="add_car_submit" class="btn btn-rounded">Add Car</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 text-center my-auto">
            <h3 class="display-5 mt-5">
                Add and Proceed....
            </h3>
            <p>
                Explore the possibilities of adding your car to our diverse fleet. Unleash the potential for exciting journeys and incredible experiences. Join us in creating a community of convenience and accessibility. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, optio in? Amet vitae fugiat voluptatibus accusamus perferendis dolores reiciendis nam!
            </p>
        </div>

    </div>
</section>


<script>
    function toggleDateForm(selectElement) {
        var dateForm = document.getElementById('dateForm');

        // Show/hide date and time form based on selected option
        if (selectElement.value === 'date_time') {
            dateForm.style.display = 'block';
        } else {
            dateForm.style.display = 'none';
        }
    }

    function addAvailabilityRow() {
        var availabilityRows = document.getElementById('availabilityRows');
        var newRow = document.createElement('div');
        newRow.className = 'row mb-3';
        newRow.innerHTML = `
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
    `;
        availabilityRows.appendChild(newRow);
    }
</script>





<?php include '../app/components/footer.php'; ?>