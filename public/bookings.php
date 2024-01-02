<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
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

// Fetch all bookings done by the driver
$sql = "SELECT * FROM bookings WHERE user_id = $userId ORDER BY booked_at DESC";
$result = $conn->query($sql);

// Initialize an empty array to store the results
$bookingsArray = [];

while ($row = $result->fetch_assoc()) {
    // Fetch car details for each booking
    $carId = $row['car_id'];
    $carSql = "SELECT * FROM cars WHERE car_id = $carId";
    $carResult = $conn->query($carSql);
    $car = $carResult->fetch_assoc();

    // Combine car details with booking details
    $bookingDetails = [
        'car_id' => $carId,
        'car_image_name' => $car['car_image_name'],
        'car_make' => $car['car_make'],
        'car_model' => $car['car_model'],
        'car_registration' => $car['car_registration'],
        'location' => $car['address_to_pickup'],
        'price' => $car['price'],
        'booked_at' => $row['booked_at'],
        'amount' => $row['amount'],
        'booked_time_slot' => $row['booked_time_slot'],
    ];

    $bookingsArray[] = $bookingDetails;
}


?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php include '../app/components/driverSidebar.php'; ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- main -->
<section class="my-5 container-fluid" id="bookings">
    <?php
    if ($bookingsArray == []) {
        echo '<h4 class="display-4">No bookings found</h4>';
    } else {
        echo '<section class="my-5 px-3 container-fluid">';
        echo '<div class="row row-cols-1 row-cols-md-3 g-4">';

        foreach ($bookingsArray as $booking) {
            $checkFeedbackQuery = "SELECT * FROM car_feedback WHERE car_id = {$booking['car_id']} AND user_id = $userId";
            $checkFeedbackResult = $conn->query($checkFeedbackQuery);
            echo '<div class="col">';
            echo '<div class="card">';
            echo '<img src="../public/assets/img/uploads/' . $booking['car_image_name'] . '" class="card-img-top" alt="car-image" style="height: 15rem;">';
            echo '<div class="card-body">';
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            echo '<h5 class="card-title cars-listing" style="width: fit-content !important;">' . htmlspecialchars($booking['car_make']) . ' <i class="bx bxs-car"></i>' . '</h5>';
            echo '<p><small class="text-muted">Car Registration: </small>' . htmlspecialchars($booking['car_registration']) . '</p>';
            echo '<p><small class="text-muted">Location: </small>' . htmlspecialchars($booking['location']) . '</p>';
            echo '<p><small class="text-muted">Slot: </small>' . htmlspecialchars($booking['booked_time_slot']) . '</p>';
            echo '</div>';
            echo '<div class="col-lg-6">';
            echo '<p><small class="text-muted">Car Model: </small>' . htmlspecialchars($booking['car_model']) . '</p>';
            echo '<p><small class="text-muted">Price: </small> £' . htmlspecialchars($booking['price']) . '/hour</p>';
            echo '<p><small class="text-muted">Booked At: </small>' . htmlspecialchars($booking['booked_at']) . '</p>';
            if ($checkFeedbackResult->num_rows === 0) {
                // No feedback found, so display the "Give Feedback" button
                echo '<button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-car-id="' . $booking['car_id'] . '">Give Feedback & Finish Ride</button>';
            } else {
                // Feedback already given, don't display the "Give Feedback" button
                echo '<button disabled class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-car-id="' . $booking['car_id'] . '">Give Feedback</button>';
            }
            echo '</div>';
            echo '<div class = "col-lg-12 cars-listing mx-auto d-flex justify-content-center">';
            echo 'Deposit Amount Paid: </small>' . htmlspecialchars($booking['amount']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</section>';
    }
    ?>

</section>


<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Give Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="feedbackForm">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" value="1" required>
                            <label class="form-check-label">1</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" value="2" required>
                            <label class="form-check-label">2</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" value="3" required>
                            <label class="form-check-label">3</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" value="4" required>
                            <label class="form-check-label">4</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" value="5" required>
                            <label class="form-check-label">5</label>
                        </div>

                        <!-- Repeat the above form-check for other ratings as needed -->
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback:</label>
                        <textarea class="form-control" name="feedback" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    <input type="hidden" id="carIdInput" name="car_id">
                </form>
            </div>
        </div>
    </div>
</div>


<!-- footer -->
<?php include '../app/components/footer.php'; ?>