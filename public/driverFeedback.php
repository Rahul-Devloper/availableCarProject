<?php
// Include your connection file
include '../app/config/connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'driver') {
    // Redirect to the login page or display an error message
    header("Location: signIn.php");
    exit();
}

// Get the logged-in user's ID
$driverId = $_SESSION['userId'];

// Fetch all feedback for the logged-in driver
$feedbackQuery = "SELECT driver_feedback.*, users.first_name
                  FROM driver_feedback
                  INNER JOIN users ON driver_feedback.user_id = users.user_id
                  WHERE driver_feedback.driver_id = $driverId order by created_at desc";

$feedbackResult = $conn->query($feedbackQuery);



// Initialize an array to store feedback data
$feedbackArray = [];

// Fetch feedback data
while ($row = $feedbackResult->fetch_assoc()) {
    $feedbackArray[] = [
        'id' => $row['id'],
        'rating' => $row['rating'],
        'feedback_text' => $row['feedback_text'],
        'created_at' => $row['created_at'],
        'first_name' => $row['first_name'],
    ];
}

// Close the MySQL connection
$conn->close();
?>

<!-- Include your header, navbar, or any other necessary components -->
<?php include '../app/components/header.php'; ?>
<?php include '../app/components/navbar.php'; ?>

<!-- Include your sidebar or any other necessary components -->
<?php include '../app/components/driverSidebar.php'; ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- Main content for feedback page -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4 text-center">Your Feedback from Car Owners</h3>

    <?php if (empty($feedbackArray)) : ?>
        <p class="text-center">No feedback available.</p>
    <?php else : ?>
        <div class="row d-flex justify-content-center align-items-center mt-5">
            <div class="col-lg-8">
                <?php foreach ($feedbackArray as $feedback) : ?>
                    <div class="card mb-3">
                        <div class="card-header" style="background-color: #0dcaf0; color: white; font-size: larger;">
                            Car Owner: <?php echo ucfirst($feedback['first_name']); ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Rating: <i class="bi bi-star-fill"></i><?php echo $feedback['rating']; ?></h5>
                            <p class="card-text"><?php echo $feedback['feedback_text']; ?></p>
                            <p class="card-text"><small class="text-muted">Date and Time: <?php echo $feedback['created_at']; ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- Include your footer or any other necessary components -->
<?php include '../app/components/footer.php'; ?>