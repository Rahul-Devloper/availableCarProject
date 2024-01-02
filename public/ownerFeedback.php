<?php
include '../app/config/connection.php';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: signIn.php");
    exit();
}

$userId = $_SESSION['userId'];

// Fetch feedback for the logged-in user from car_feedback table
$carFeedbackQuery = "SELECT car_feedback.*, cars.car_make, cars.car_model, users.first_name AS user_first_name
                    FROM car_feedback
                    INNER JOIN cars ON car_feedback.car_id = cars.car_id
                    INNER JOIN users ON car_feedback.user_id = users.user_id
                    WHERE cars.user_id = $userId order by created_at desc";

$carFeedbackResult = $conn->query($carFeedbackQuery);

// Fetch the user's first name
$userQuery = "SELECT first_name FROM users WHERE user_id = $userId";
$userResult = $conn->query($userQuery);
$userData = $userResult->fetch_assoc();
$userFirstName = $userData['first_name'];

?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>


<!-- Include your sidebar or any other necessary components -->
<?php include '../app/components/ownerSidebar.php'; ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- Feedback page start -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4 text-center">
        Feedback from Drivers
    </h3>

    <?php if ($carFeedbackResult->num_rows > 0) : ?>
        <div class="row justify-content-center align-items-center d-flex mt-5">
            <?php while ($feedback = $carFeedbackResult->fetch_assoc()) : ?>
                <div class="col-lg-8">
                    <div class="card mb-3">
                    <div class="card-header" style="background-color: #0dcaf0; color: white; font-size: larger;">
                            <?php echo ucfirst($feedback['user_first_name']); ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Rating: <i class="bi bi-star-fill"></i><?php echo $feedback['rating']; ?></h5>
                            <p class="card-text"><?php echo $feedback['feedback_text']; ?></p>
                            <p class="card-text">Car: <?php echo $feedback['car_make'] . ' ' . $feedback['car_model']; ?></p>
                            <p class="card-text">Date: <?php echo $feedback['created_at']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p class="text-center">No feedback available for your cars.</p>
    <?php endif; ?>
</section>
<!-- Feedback page end -->

<!-- footer -->
<?php include '../app/components/footer.php'; ?>
