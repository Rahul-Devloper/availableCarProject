<?php
// Include your database connection file
include '../app/config/connection.php';


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $driverId = $_POST['driver_id'];
    $rating = $_POST['rating'];
    $feedbackText = $_POST['feedback'];
    $userId = $_SESSION['userId'];

    // Perform the SQL query to insert feedback into the database
    $sql = "INSERT INTO driver_feedback (driver_id, rating, feedback_text, user_id) VALUES ('$driverId', '$rating', '$feedbackText', '$userId')";
    $result = $conn->query($sql);

    if ($result) {
        echo "Feedback submitted successfully";
        // Redirect to another page or display a success message
    } else {
        echo "Error submitting feedback: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
