<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
$firstName = '';
session_start();

// check if user is logged in
if ($_SESSION['role'] == 'admin') {
    header("Location: adminDashboard.php");
    exit();
} else if ($_SESSION['role'] == 'owner') {
    header("Location: 404.php");
    exit();
}
if ($_SESSION == NULL) {
    // print_r($_SESSION);
    header("Location: signIn.php");
    exit();
}
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
    $firstName = $_SESSION['firstName'];
}

// Fetch total amount spent and number of bookings
$userId = $_SESSION['userId'];

// Fetch booked_at data for the particular user
$sql = "SELECT MONTH(booked_at) AS month, COUNT(*) AS bookingCount
        FROM bookings
        WHERE user_id = $userId
        AND YEAR(booked_at) = YEAR(CURDATE())
        GROUP BY MONTH(booked_at)
        ORDER BY MONTH(booked_at)";

// Execute the query
$result = $conn->query($sql);

// Initialize arrays to store data for the chart
$labels = [];
$data = [];


// Fetch data from the result set
while ($row = $result->fetch_assoc()) {
    $monthNumber = $row['month'];
    $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
    $labels[] = $monthName;
    $data[] = $row['bookingCount'];
}



$sqlAmountAndBookings = "SELECT SUM(amount) AS totalAmount, COUNT(*) AS totalBookings FROM bookings WHERE user_id = $userId";
$resultAmountAndBookings = $conn->query($sqlAmountAndBookings);
$rowAmountAndBookings = $resultAmountAndBookings->fetch_assoc();
$totalAmountSpent = $rowAmountAndBookings['totalAmount'];
$totalBookings = $rowAmountAndBookings['totalBookings'];


?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/driverSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- dashbard start -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4">
        Welcome <a href="driverProfile.php" class="text-decoration-none" style="color: #0dcaf0;"><?php echo $firstName ?></a>
    </h3>
</section>
<!-- dashboard end -->

<!-- driverDashboard start -->
<section class="my-5 px-3">
    <div class="row">
        <div class="col-lg-6 my-auto">
            <div class="col-lg-12 d-flex mb-4 justify-content-end">
                <div class="card" style="width: 75%;">
                    <div class="card-body">
                        <h5 class="card-title">Your Total Bookings: <?php echo $totalBookings; ?></h5>
                        <span class="btn-modification text-center mt-4">
                            <a href="bookings.php" class="btn  btn-rounded" style="width: 30%;">View</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 d-flex mb-4 justify-content-end">
                <div class="card" style="width: 75%;">
                    <div class="card-body">
                        <h5 class="card-title">Total Amount Spent: £<?php echo isset($totalAmountSpent) ? $totalAmountSpent : '0.00'; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 d-flex mb-4 justify-content-end">
                <div class="card" style="width: 75%;">
                    <div class="card-body">
                        <?php
                    // Fetch and display the average rating from driver_feedback table
                    $ratingQuery = "SELECT AVG(rating) AS avgRating FROM driver_feedback WHERE driver_id = $userId";
                    $ratingResult = $conn->query($ratingQuery);
                    $avgRating = $ratingResult->fetch_assoc()['avgRating'];

                    if (!empty($avgRating)) {
                        echo '<h5 class="card-title">Overall Rating: ' . number_format($avgRating, 1) . '</h5>';
                    } else {
                        echo '<h5 class="card-title">Overall Rating: ' . 'N/A' . '</h5>';
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card bookingsTime" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings Over Time (current year)</h5>
                    <canvas id="totalBookingsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- driverDashboard end -->



<script>
    var ctx = document.getElementById('totalBookingsChart').getContext('2d');

    var data = <?php echo json_encode($data); ?>;
    console.log('data=>', data);
    var labels = <?php echo json_encode($labels); ?>;

    var bookingChart;

    if (data.length > 0) {
        // If there is data, create the chart
        bookingChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Bookings(current year)',
                    data: data,
                    backgroundColor: [
                        'rgb(255, 99, 132, 0.5)',
                        'rgb(54, 162, 235, 0.5)',
                        'rgb(255, 206, 86, 0.5)',
                        'rgb(75, 192, 192, 0.5)',
                        'rgb(153, 102, 255, 0.5)',
                        'rgb(255, 159, 64, 0.5)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    } else {
        // If there is no data, display a message or set a default color
        // For example, you can set a default color for the card
        var card = document.querySelector('.bookingsTime');
        card.style.backgroundColor = 'lightgray';
        card.innerHTML = '<div class="card-body"><p class="card-text">No data available </br> Looks like you have no bookings this year!!</p></div>';
    }
</script>
<!-- footer -->
<?php include '../app/components/footer.php'; ?>