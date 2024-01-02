<!-- include connection -->
<?php include '../app/config/connection.php' ?>


<?php
session_start();
// print_r($_SESSION);

//formatting the date and time to proper dd/mm/yyyy hh:mm
$startDateTime = new DateTime($_SESSION['startDateTime']);
$endDateTime = new DateTime($_SESSION['endDateTime']);

// Format the date and time in UK format
$formattedStartDateTime = $startDateTime->format('d/m/Y H:i');
$formattedEndDateTime = $endDateTime->format('d/m/Y H:i');

$booked_time_slot = $formattedStartDateTime . ' - ' . $formattedEndDateTime;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $car_id = intval($_GET['id']);
    $sql = "SELECT cars.*, car_geo_location.latitude, car_geo_location.longitude
        FROM cars
        LEFT JOIN car_geo_location ON cars.car_id = car_geo_location.car_id
        WHERE cars.car_id = $car_id;
        ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
        // print_r($car);

        //         echo '<script>
        //     function initMap() {
        //         var mapDiv = document.getElementById("map");
        //         var map = new google.maps.Map(mapDiv, {
        //             center: {lat: ' . $car['latitude'] . ', lng: ' . $car['longitude'] . '},
        //             zoom: 15
        //         });

        //         var marker = new google.maps.Marker({
        //             position: {lat: ' . $car['latitude'] . ', lng: ' . $car['longitude'] . '},
        //             map: map,
        //             title: "Car Location"
        //         });
        //     }
        // </script>';

        // Add the Google Maps JavaScript API script
        // echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiIKcQ1Gdk6vO8ARVez1nOlXuMhXI2Mcw&callback=initMap" async defer></script>';
    } else {
        // echo 'Car not found.';
        // display an alert message
        echo '<script>alert("Car not found.")</script>';
    }
}

?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'driver') {
    include '../app/components/driverSidebar.php';
    include '../app/components/sidebarButton.php';
}  ?>




<section class="container-fluid">
    <div class="my-5">
        <h3 class="display-4 text-center">Car Details</h3>
    </div>

    <div class="card mb-3 mx-auto my-3" style="max-width: 90%;">
        <div class="row g-0">
            <div class="col-md-5 d-flex justify-content-center align-items-center">
                <img style="width: 90%;" src="../public/assets/img/uploads/<?php echo $car['car_image_name']; ?>" class="img-fluid rounded-start mr-2" alt="car_image">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <form action="bookCar.php?id=<?php echo $car['car_id']; ?>" method="POST" enctype="multipart/form-data" class="mx-auto text-color">
                        <!-- Car details input fields -->
                        <div class="row text-muted" style="color: white;">
                            <div class="col-md-6 mb-3">
                                <span>Car Make:</span>
                                <span class='car-listing-view'><?php echo $car['car_make']; ?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span>Car Model:</span>
                                <span class='car-listing-view'><?php echo $car['car_model']; ?></span>
                            </div>
                        </div>

                        <div class="row text-muted mb-3" style="color: white;">
                            <div class="col-md-6">
                                <span>Car Registration:</span>
                                <span class='car-listing-view'><?php echo $car['car_registration']; ?></span>
                            </div>
                            <div class="col-md-6">
                                <span>Car Type:</span>
                                <span class='car-listing-view'><?php echo $car['car_type']; ?></span>
                            </div>
                        </div>

                        <div class="row text-muted mb-3" style="color: white;">

                            <div class="col-md-6">
                                <span>Price Per Hour:</span>
                                <span class='car-listing-view'>£<?php echo $car['price']; ?></span>
                            </div>
                            <div class="col-md-6">
                                <span>Postal Code:</span>
                                <span class='car-listing-view'><?php echo $car['postal_code']; ?></span>
                            </div>

                            <div class="col-md-12 mt-3">
                                <?php if ($car['car_availability_type'] == 'date_time' && $car['is_available'] == 0) {
                                    $availabilitySchedule = $car['availability_schedule'];

                                    $scheduleArray = json_decode($availabilitySchedule, true);

                                    foreach ($scheduleArray as $day => $times) {
                                        echo '<span class="badge rounded-pill text-bg-primary me-2">' . ucfirst($day) . ': ';

                                        foreach ($times as $time) {
                                            list($startTime, $endTime) = explode('-', $time);
                                            echo $startTime . ' - ' . $endTime;
                                        }

                                        echo '</span>';
                                    }
                                }
                                ?>
                            </div>

                            <?php if ($car['car_availability_type'] == 'always' && $car['is_available'] == 1) { ?>
                                <div class="col-md-6 mt-3">
                                    <span>Availability:</span>
                                    <span class='car-listing-view'>Available</span>
                                </div>

                            <?php } ?>

                        </div>
                        <div class="row text-muted mb-3" style="color: white;">
                            <div class="col-md-12">
                                <!-- address_to_pickup -->
                                <span>Address To Pickup and Return:</span>
                                <span class='car-listing-view'><?php echo $car['address_to_pickup']; ?></span>
                            </div>
                        </div>


                        <!-- <a
        href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $car['latitude']; ?>,<?php echo $car['longitude']; ?>"
        target="_blank"
    >
        <iframe
            width="100%"
            height="400"
            frameborder="0"
            style="border:0"
            src="https://www.google.com/maps/embed/v1/view?key=AIzaSyCiIKcQ1Gdk6vO8ARVez1nOlXuMhXI2Mcw&center=<?php echo $car['latitude']; ?>,<?php echo $car['longitude']; ?>&zoom=15"
            allowfullscreen
        ></iframe>
    </a> -->
                        <a href="https://www.google.com/maps/place/<?php echo urlencode($car['address_to_pickup']); ?>" target="_blank">
                            <div id="map" style="height: 400px;"></div>

                            <script>
                                // Initialize the Google Map
                                function initMap() {
                                    var carLocation = {
                                        lat: <?php echo $car['latitude']; ?>,
                                        lng: <?php echo $car['longitude']; ?>
                                    };
                                    var map = new google.maps.Map(document.getElementById('map'), {
                                        center: carLocation,
                                        zoom: 15,
                                        disableDefaultUI: true, // Disable default UI controls
                                        streetViewControl: false // Disable street view control
                                    });

                                    // Add a marker to the map
                                    var marker = new google.maps.Marker({
                                        position: carLocation,
                                        map: map,
                                        title: 'Car Location'
                                    });
                                }
                            </script>

                            <!-- Include the Google Maps JavaScript API -->
                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiIKcQ1Gdk6vO8ARVez1nOlXuMhXI2Mcw&callback=initMap" async defer></script>
                        </a>

                        <!-- Submit button to add a car -->
                        <!-- <div class="col-12 btn-modification mt-5">
                            <button type="submit" name="car_book" class="btn btn-rounded"></button>
                        </div> -->
                        <!-- Add the PayPal Button Container -->
                        <?php echo isset($_SESSION['userId']) ? '<div id="paypal-button-container"></div>' : '<a href = "signIn.php" class = "btn btn-rounded">Please log in to complete booking</a>' ; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="display-6">
                    Please note that you must have a PayPal account to book a car and you need to pay a safety deposit of <b>£20</b>.
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->



<script src="https://www.paypal.com/sdk/js?client-id=AV6wrYbSjSe3HF8xSYo1Ejsmj-M-z8AiozJA31piu1Xs-cNHz0-26XxwTdV4eDS5HxnSU3WKshPG4jnU&currency=GBP"></script>

<!-- Include Your Custom JavaScript Code -->
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            // Set up the transaction
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '20.00',
                        currency_code: 'GBP'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            // Capture the funds from the transaction
            return actions.order.capture().then(function(details) {
                // Display a success message to the buyer
                alert('Transaction completed by ' + details.payer.name.given_name);
                 

                // Insert data into the bookings table
                insertBookingData(details.id, <?php echo $car['car_id']; ?>, <?php echo $_SESSION['userId']; ?>, details.purchase_units[0].amount.value, details.id, '<?php echo $booked_time_slot; ?>');

                // Redirect to the bookings page
                // window.location.href = 'bookings.php';
            });
        }
    }).render('#paypal-button-container');

    // Function to insert data into the bookings table
    function insertBookingData(transactionId, carId, userId, amount, transactionReferenceId, bookedTimeSlot) {

        console.log("insertBookingData function reached")
        console.log({booked_time_slot: bookedTimeSlot})
    // Make an Ajax request to insert data into the bookings table
    $.ajax({
        type: 'POST',
        url: 'insert_booking.php',
        data: {
            transaction_id: transactionId,
            car_id: carId,
            user_id: userId,
            amount: amount,
            transaction_reference_id: transactionReferenceId,
            booked_time_slot: bookedTimeSlot
        },
        success: function(response) {
            console.log('Success')
            console.log("Response: " + response);
            if (response.status === 'success') {
                console.log('Booking data inserted successfully.');
                // Redirect to the bookings page
                window.location.href = 'bookings.php';
            } else {
                console.error('Error inserting booking data: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax request failed: ' + error);
        }
    });
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        myModal.show();
    });
</script>
<!-- Footer -->
<?php include '../app/components/footer.php'; ?>