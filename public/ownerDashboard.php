<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
$firstName = '';
session_start();
if ($_SESSION['role'] == 'admin') {
    header("Location: adminDashboard.php");
    exit();
} else if ($_SESSION['role'] == 'driver') {
    header("Location: 404.php");
    exit();
}
// check if user is logged in
if ($_SESSION == NULL) {
    // print_r($_SESSION);
    header("Location: signIn.php");
    exit();
}
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
    $firstName = $_SESSION['firstName'];
}
?>

<?php
$sqlTotalBookings = "
    SELECT 
    b.*, 
    c.car_make, 
    c.car_model, 
    c.car_registration, 
    u.first_name, 
    u.last_name,
    SUM(b.amount) as total_amount_collected
FROM bookings b
JOIN cars c ON b.car_id = c.car_id
JOIN users u ON b.user_id = u.user_id
WHERE c.user_id = $_SESSION[userId]
GROUP BY b.id
ORDER BY b.booked_at DESC;
";
$resultTotalBookings = $conn->query($sqlTotalBookings);

// Query to get total booked and unbooked cars
$sqlTotalCars = "SELECT 
                    SUM(CASE WHEN booking_status = 'booked' THEN 1 ELSE 0 END) as bookedCars,
                    SUM(CASE WHEN booking_status = 'available' THEN 1 ELSE 0 END) as unbookedCars
                FROM cars
                WHERE user_id = $_SESSION[userId]";

$resultTotalCars = $conn->query($sqlTotalCars);
$rowTotalCars = $resultTotalCars->fetch_assoc();

// Calculate percentages
$totalCars = $rowTotalCars['bookedCars'] + $rowTotalCars['unbookedCars'];
$percentageBooked = ($totalCars > 0) ? ($rowTotalCars['bookedCars'] / $totalCars) * 100 : 0;
$percentageUnbooked = ($totalCars > 0) ? ($rowTotalCars['unbookedCars'] / $totalCars) * 100 : 0;

?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/ownerSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- dashbard start -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4">
        Welcome <a href="ownerProfile.php" class="text-decoration-none" style="color: #0dcaf0;"><?php echo $firstName ?></a>
    </h3>
</section>
<!-- dashboard end -->

<!-- ownerDashboard start -->

<section class="my-5">
    <div class="row">
        <!-- Display Total Cars Pie Chart -->
        <div class="col-lg-6 d-flex mb-4 justify-content-center align-items-center">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Percentage Distribution of Booking Status for Cars (Today)</h5>
                    <canvas id="totalCarsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Display Total Revenue DataTable -->
        <div class="col-lg-6 d-flex mb-4 justify-content-start">
            <section class="my-5 px-3">
                <div class="row">
                    <div class="col-lg-6 d-flex mb-4 justify-content-end">
                        <div class="card" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Total Cars: <?php echo $totalCars ?></h5>
                                <span class="btn-modification text-center mt-4">
                                    <a href="cars.php" class="btn  btn-rounded" style="width: 30%;">View</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex mb-4 justify-content-start">
                        <div class="card" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Total Bookings: <?php echo $resultTotalBookings->num_rows ?> </h5>
                                <span class="btn-modification text-center mt-4">
                                    <a href="ownerBookings.php" class="btn  btn-rounded" style="width: 30%;">View</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex mb-4 justify-content-end">
                        <div class="card" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Total Revenue Statistics: £<?php echo $resultTotalBookings->num_rows * 20 ?></h5>
                                <!-- <a href="#" class="card-link">View Owners</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex mb-4 justify-content-start">
                        <div class="card" style="width: 100%;">
                            <div class="card-body">
                                <?php
                                $averageRatingQuery = "SELECT AVG(rating) AS avg_rating
                                    FROM car_feedback
                                    INNER JOIN cars ON car_feedback.car_id = cars.car_id
                                    WHERE cars.user_id = $_SESSION[userId]";

                                $averageRatingResult = $conn->query($averageRatingQuery);
                                $averageRatingData = $averageRatingResult->fetch_assoc();
                                $averageRating = $averageRatingData['avg_rating'];
                                if (!empty($averageRating)) {
                                    echo '<h5 class="card-title">Overall Rating: ' . number_format($averageRating, 1) . '</h5>';
                                } else {
                                    echo '<h5 class="card-title">Overall Rating: ' . 'N/A' . '</h5>';
                                }
                                ?>
                                <!-- <a href="#" class="card-link">View Owners</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 d-flex mb-4 justify-content-center">
                        <div class="card" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Total Revenue (£) vs. Bookings</h5>
                                <canvas id="revenueVsBookingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</section>

<!-- ownerDashboard end -->


<!-- Initialize Total Cars Pie Chart -->
<script>
    var ctx = document.getElementById('totalCarsChart').getContext('2d');
    var totalCarsChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Booked Cars', 'Unbooked Cars'],
            datasets: [{
                data: [<?php echo $percentageBooked; ?>, <?php echo $percentageUnbooked; ?>],
                backgroundColor: ['#FF6384', '#36A2EB'],
            }],
        },
        options: {
            responsive: true,
        }
    });
    // Initialize Total Revenue vs. Bookings Bar Chart
    var ctx = document.getElementById('revenueVsBookingsChart').getContext('2d');
    var revenueVsBookingsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Revenue', 'Total Bookings'],
            datasets: [{
                    label: 'Total Revenue',
                    data: [<?php echo $resultTotalBookings->num_rows * 20; ?>, 0],
                    backgroundColor: '#00ff87',
                    borderColor: '#00ff87',
                    borderWidth: 1
                },
                {
                    label: 'Total Bookings',
                    data: [0, <?php echo $resultTotalBookings->num_rows; ?>],
                    backgroundColor: '#ff930f',
                    borderColor: '#ff930f',
                    borderWidth: 1
                }
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>


<!-- Initialize Total Revenue DataTable -->
<script>
    $(document).ready(function() {
        $('#revenueTable').DataTable();
    });
</script>
<!-- footer -->
<?php include '../app/components/footer.php'; ?>