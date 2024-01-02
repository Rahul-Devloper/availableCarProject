<?php
// Include your database connection script
include '../app/config/connection.php';

// Get data from the Ajax request
$transactionId = mysqli_real_escape_string($conn, $_POST['transaction_id']);
$carId = mysqli_real_escape_string($conn, $_POST['car_id']);
$userId = mysqli_real_escape_string($conn, $_POST['user_id']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);
$transactionReferenceId = mysqli_real_escape_string($conn, $_POST['transaction_reference_id']);
$booked_time_slot = mysqli_real_escape_string($conn, $_POST['booked_time_slot']);

// Insert data into the bookings table
$sqlBookings = "INSERT INTO bookings (car_id, user_id, amount, transaction_reference_id, booked_at, booked_time_slot)
                VALUES ('$carId', '$userId', '$amount', '$transactionReferenceId', NOW(), '$booked_time_slot')";

$response = array();

// Check if the bookings table query was successful
if ($conn->query($sqlBookings) === TRUE) {
    // Update the booking_status in the cars table to 'booked'
    $sqlUpdateCarStatus = "UPDATE cars SET booking_status = 'booked' WHERE car_id = '$carId'";

    // Check if the update query was successful
    if ($conn->query($sqlUpdateCarStatus) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Booking data inserted and car status updated successfully.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error updating car status: ' . print_r($conn->error);
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error inserting booking data: ' . print_r($conn->error);
}

// Close the database connection
$conn->close();

// Send JSON response back to the Ajax request
header('Content-Type: application/json');
echo json_encode($response);
?>
