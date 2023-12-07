<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<!-- get session values -->
<?php
$firstName = '';
$email = '';
$userId = '';
session_start();
print_r($_SESSION);
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
echo $firstName;
echo $email;
echo $userId;
$sql = "";
$errorMsg = "";
if (isset($_POST['add_car_submit']) && isset($_FILES['car_image']['name']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $car_make = $_POST['car_make'];
    $car_model = $_POST['car_model'];
    $car_type = $_POST['car_type'];
    $car_availability_type = $_POST['car_availability_type'];
    $car_address = $_POST['car_address'];
    $car_registration = $_POST['car_registration'];
    $price = $_POST['price'];

    // ===image upload===
    print_r($_FILES);
    $target_dir = "assets/img/uploads/";
    $car_image_name = $userId . "_" . basename($_FILES["car_image"]["name"]);
    $target_file = $target_dir . $userId . "_" . basename($_FILES["car_image"]["name"]);
    echo $target_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["car_image"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["car_image"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["car_image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }




    if ($car_availability_type == 'always') {
        echo 'always';
        echo $userId;
        $sql = "INSERT INTO cars (user_id, car_make, car_model, car_registration, car_type, car_availability_type, address_to_pickup, availability_schedule, is_available, car_image_name, price)
VALUES ( $userId, '$car_make', '$car_model', '$car_registration', '$car_type', '$car_availability_type', '$car_address', NULL, true, '$car_image_name', '$price')";
    } else {
        echo $userId;
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
            print_r($jsonAvailabilityData);

            $sql = "INSERT INTO cars (user_id, car_make, car_model, car_registration, car_type, car_availability_type, address_to_pickup, availability_schedule, is_available, car_image_name, price)
            VALUES ('$userId', '$car_make', '$car_model', '$car_registration', '$car_type', '$car_availability_type', '$car_address', '$jsonAvailabilityData', false, '$car_image_name', '$price')";
        }


        //        
    }

    if ($conn->query($sql) === TRUE) {
        echo "New Car created successfully";
        header("Location: ../public/cars.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else if (isset($_POST['add_car_submit']) && !isset($_FILES['car_image']['name']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $errorMsg = "Please ensure to fill all fields";
    echo "Error: ENsure to fill all fields correctly " . "<br>";
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

            // Add more cases if you have additional roles

        default:
            // Handle cases where the role doesn't match any of the expected values
            break;
    }
} ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- The sidebar -->
<section id='content-wrapper' class="my-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="container content w-85">
                <div>
                    <!-- Add car form (to be implemented) -->
                    <h3 class="text-center display-6">Add Your Car</h3>
                    <form action="addCar.php" method="POST" enctype="multipart/form-data" class="box-design mx-auto text-color" style="width: 70%;">
                        <!-- show errorMsg if any -->
                        <?php if ($errorMsg) echo "<div class='red-text'>$errorMsg</div>"; ?>
                        <!-- Car details input fields -->
                        <div class="row" style="color: white;">
                            <div class="col-md-6 mb-3">
                                <label for="carMake">Car Make:</label>
                                <input name="car_make" type="text" class="form-control" id="carMake" placeholder="Toyota" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="carModel">Car Model:</label>
                                <input name="car_model" type="text" class="form-control" id="carModel" placeholder="Camry" required>
                            </div>
                        </div>

                        <div class="row mb-3" style="color: white;">
                            <div class="col-md-6">
                                <label for="carRegistration">Car Registration:</label>
                                <input name="car_registration" type="text" class="form-control" id="carRegistration" placeholder="ABC-1234" required>
                            </div>
                            <div class="col-md-6">
                                <label for="carImage">Car Image: (PNG)</label>
                                <input name="car_image" type="file" class="form-control" id="carImage">
                            </div>
                        </div>

                        <div class="row mb-3" style="color: white;">
                            <div class="col-md-6">
                                <label for="carType">Car Type:</label>
                                <input name="car_type" type="text" class="form-control" id="carType" placeholder="SUV, Sedan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price">Price Per Hour:</label>
                                <input name="price" type="text" class="form-control" id="carPrice" placeholder="30" required>
                            </div>
                            <div class="col-md-6">
                                <label for="postalCode">Postal Code:</label>
                                <input name="postal_code" type="text" class="form-control" id="postalCode" placeholder="AB1 2CD" required>
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
                                            <!-- <select name="car_availability_day[]" class="form-select" aria-label="Default select example"">
                                                <option selected>Select</option>
                                                <option value=" Sunday">Sunday</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
                                            </select> -->

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
        <div class="col-lg-6">
            <h3 class="display-5 mt-5">
                Add and Proceed....
            </h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, optio in? Amet vitae fugiat voluptatibus accusamus perferendis dolores reiciendis nam!
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