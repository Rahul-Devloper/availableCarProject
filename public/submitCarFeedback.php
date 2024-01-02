<?php
// Include your database connection file
include '../app/config/connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $carId = $_POST['car_id'];
    $rating = $_POST['rating'];
    $feedbackText = $_POST['feedback'];
    $userId = $_SESSION['userId'];

    // Perform the SQL query to insert feedback into the database
    $sqlInsertFeedback = "INSERT INTO car_feedback (car_id, rating, feedback_text, user_id) VALUES ('$carId', '$rating', '$feedbackText', '$userId')";
    $resultInsertFeedback = $conn->query($sqlInsertFeedback);

    // Update booking status to available
    $sqlUpdateBookingStatus = "UPDATE cars SET booking_status = 'available' WHERE car_id = '$carId'";
    $resultUpdateBookingStatus = $conn->query($sqlUpdateBookingStatus);

    if ($resultInsertFeedback && $resultUpdateBookingStatus) {
        echo "Feedback submitted successfully";
        // Redirect to another page or display a success message
    } else {
        echo "Error submitting feedback: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
