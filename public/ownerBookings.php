<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
//handling the post request from makeAvailable button
if (isset($_POST['carId'])) {
    $car_id = $_POST['carId'];
    $sql = "UPDATE cars SET booking_status = 'available' WHERE car_id = '$car_id'";
    $conn->query($sql);
}

?>

<?php
    session_start();
    // Get the user ID from the session
    $userId = $_SESSION['userId'];

    // Query to fetch owner bookings
    $sql = "
        SELECT b.*, c.car_make, c.car_model, c.car_registration, c.booking_status, u.first_name, u.last_name
        FROM bookings b
        JOIN cars c ON b.car_id = c.car_id
        JOIN users u ON b.user_id = u.user_id
        WHERE c.user_id = $userId order by b.booked_at desc;
    ";

    // Execute the query
    $result = $conn->query($sql);

    // Initialize an array to store the results
    $ownerBookings = [];

    // Check if there are any results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add each row to the ownerBookings array
            $ownerBookings[] = $row;
        }
    }

?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/ownerSidebar.php'; ?>

<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- main -->
<section class="my-5 container-fluid">
    <!-- Display owner bookings -->
    <table class="table">
    <thead>
        <tr>
            <th class="text-center" scope="col">Role No</th>
            <th class="text-center" scope="col">Booking ID</th>
            <th class="text-center" scope="col">Car Make</th>
            <th class="text-center" scope="col">Car Model</th>
            <th class="text-center" scope="col">Customer Name</th>
            <th class="text-center" scope="col">Booked Time Slot</th>
            <th class="text-center" scope="col">Booked At</th>
            <th class="text-center" scope="col">Amount</th>
            <th class="text-center" scope="col">Actions</th>
            <!-- Add more headings as needed -->
        </tr>
    </thead>
    <tbody>
        <?php
        $roleNumber = 1; // Initialize the role number outside the loop

        foreach ($ownerBookings as $booking):
            $checkFeedbackQuery = "SELECT * FROM driver_feedback WHERE driver_id = {$booking['user_id']} AND user_id = $userId";
            $checkFeedbackResult = $conn->query($checkFeedbackQuery);
        ?>
            <tr>
                <!-- Display role no for each booking -->
                <th class="text-center" scope="row"><?php echo $roleNumber; ?></th>
                <td class="text-center"><?php echo $booking['id']; ?></td>
                <td class="text-center"><?php echo $booking['car_make']; ?></td>
                <td class="text-center"><?php echo $booking['car_model']; ?></td>
                <td class="text-center"><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></td>
                <td class="text-center"><?php echo $booking['booked_time_slot']; ?></td>
                <td class="text-center"><?php echo $booking['booked_at']; ?></td>
                <td class="text-center">£<?php echo $booking['amount']; ?></td>
                <td class="text-center">
                        <?php
                        // Check if the booking_status is not 'available' to enable the button
                        if ($booking['booking_status'] !== 'available'):
                        ?>
                            <form method="post" action="ownerBookings.php" class="mb-3">
                                <input type="hidden" name="carId" value="<?php echo $booking['car_id']; ?>">
                                <button type="submit" class="btn btn-success btn-rounded" style="width: 80%;">Make Available</button>
                            </form>
                        <?php endif; ?>
                        <?php 
                        if ($checkFeedbackResult->num_rows === 0) {
                        echo ' <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#driverFeedbackModal" data-driver-id="' . $booking['user_id'] . '">Give Feedback</button> ';
                        } else {
                        echo ' <button class="btn btn-primary btn-rounded" disabled>Particular User Feedback Given</button> ';
                        }
                        ?>
                    </td>
                <!-- Add more columns as needed -->
            </tr>
            <?php
            // Increment the role number for the next iteration
            $roleNumber++;
        endforeach;
        ?>
    </tbody>
</table>

</section>

<!-- Feedback Modal -->
<div class="modal fade" id="driverFeedbackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Give Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="driverFeedbackForm">
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
                    <input type="hidden" id="driverIdInput" name="driver_id">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- footer -->
<?php include '../app/components/footer.php'; ?>
