<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
$firstName = '';
session_start();
// check if user is logged in
if ($_SESSION['role'] == 'owner') {
    header("Location: 404.php");
    exit();
} else if ($_SESSION['role'] == 'driver') {
    header("Location: 404.php");
    exit();
}

// get session values
if ($_SESSION == NULL) {
    // print_r($_SESSION);
    header("Location: signIn.php");
    exit();
}
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
    $firstName = $_SESSION['firstName'];
}

$userId = $_SESSION['userId'];


// Query to get the total number of owners
$sqlTotalOwners = "SELECT COUNT(*) as totalOwners FROM users WHERE role = 'owner'";
$resultTotalOwners = $conn->query($sqlTotalOwners);
$rowTotalOwners = $resultTotalOwners->fetch_assoc();
$totalOwners = $rowTotalOwners['totalOwners'];

// Query to get the total number of drivers
$sqlTotalDrivers = "SELECT COUNT(*) as totalDrivers FROM users WHERE role = 'driver'";
$resultTotalDrivers = $conn->query($sqlTotalDrivers);
$rowTotalDrivers = $resultTotalDrivers->fetch_assoc();
$totalDrivers = $rowTotalDrivers['totalDrivers'];


$sqlOverallRevenue = "SELECT DATE_FORMAT(booked_at, '%Y-%m') AS month, SUM(amount) AS monthlyRevenue
                    FROM bookings
                    GROUP BY month
                    ORDER BY month";

$resultOverallRevenue = $conn->query($sqlOverallRevenue);

// Initialize arrays to store data for the chart
$overallRevenueLabels = [];
$overallRevenueData = [];

// Fetch data from the result set
while ($row = $resultOverallRevenue->fetch_assoc()) {
    $overallRevenueLabels[] = $row['month'];
    $overallRevenueData[] = $row['monthlyRevenue'];
}

// Encode the data as JSON for JavaScript
$overallRevenueDataJSON = json_encode($overallRevenueData);
$overallRevenueLabelsJSON = json_encode($overallRevenueLabels);


//drivers vs owners

$sqlDriversVsOwners = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
SUM(CASE WHEN role = 'driver' THEN 1 ELSE 0 END) AS driverCount,
SUM(CASE WHEN role = 'owner' THEN 1 ELSE 0 END) AS ownerCount
FROM users
GROUP BY month
ORDER BY month;";

$resultDriversVsOwners = $conn->query($sqlDriversVsOwners);


// Initialize arrays to store data for the chart
$driversVsOwnersLabels = [];
$driversCount = [];
$ownersCount = [];

// Fetch data from the result set
while ($row = $resultDriversVsOwners->fetch_assoc()) {
    $driversVsOwnersLabels[] = $row['month'];
    $driversCount[] = $row['driverCount'];
    $ownersCount[] = $row['ownerCount'];
}

// Encode the data as JSON for JavaScript
$driversVsOwnersLabelsJSON = json_encode($driversVsOwnersLabels);
$driversCountJSON = json_encode($driversCount);
$ownersCountJSON = json_encode($ownersCount);


// Query to get monthly bookings
$sqlMonthlyBookings = "SELECT DATE_FORMAT(booked_at, '%Y-%m') AS month, COUNT(*) AS monthlyBookings
                    FROM bookings
                    GROUP BY month
                    ORDER BY month";

$resultMonthlyBookings = $conn->query($sqlMonthlyBookings);

// Initialize arrays to store data for the new bar chart
$monthlyBookingsLabels = [];
$monthlyBookingsData = [];

// Fetch data from the result set
while ($row = $resultMonthlyBookings->fetch_assoc()) {
    $monthlyBookingsLabels[] = $row['month'];
    $monthlyBookingsData[] = $row['monthlyBookings'];
}

// Encode the data as JSON for JavaScript
$monthlyBookingsDataJSON = json_encode($monthlyBookingsData);
$monthlyBookingsLabelsJSON = json_encode($monthlyBookingsLabels);



?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/adminSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- dashbard start -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4">
        Welcome <a href="adminProfile.php" class="text-decoration-none" style="color: #0dcaf0;"><?php echo $firstName ?></a>
    </h3>
</section>
<!-- dashboard end -->

<!-- adminDashboard start -->

<section class="my-5 px-3">
    <div class="row">
        <div class="col-lg-6 d-flex justify-content-end mt-3">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Drivers: <?php echo $totalDrivers; ?></h5>
                    <p class="card-text">View all drivers</p>
                    <span class="btn-modification text-center mt-4">
                        <a href="drivers.php" class="btn  btn-rounded" style="width: 30%;">View</a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex justify-content-start mt-3">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Owners: <?php echo $totalOwners; ?></h5>
                    <p class="card-text">View all owners</p>
                    <span class="btn-modification text-center mt-4">
                        <a href="owners.php" class="btn  btn-rounded" style="width: 30%;">View</a>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 d-flex justify-content-center align-items-center mt-3 mx-auto">
            <div class="card" style="width: 75%; height: fit-content">
                <div class="card-body">
                    <h5 class="card-title">Drivers vs. Owners</h5>
                    <!-- Include a canvas for the overall revenue bar chart -->
                    <canvas id="driversVsOwnersBarChart"></canvas>
                </div>
            </div>
            <div class="card ms-4" style="width: 75%; height: fit-content">
                <div class="card-body">
                    <h5 class="card-title">Monthly Bookings Bar Chart</h5>
                    <!-- Include a canvas for the monthly bookings bar chart -->
                    <canvas id="monthlyBookingsBarChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Card for Overall Revenue Chart -->
        <div class="col-lg-12 d-flex justify-content-center align-items-center mt-3">
            <div class="card" style="width: 35%; height: fit-content">
                <div class="card-body">
                    <h5 class="card-title">Overall Revenue Chart</h5>
                    <!-- Include a canvas for the overall revenue bar chart -->
                    <canvas id="overallRevenueChart"></canvas>
                </div>
            </div>
        </div>
                

    </div>
</section>

<!-- adminDashboard end -->

<script>
    // Example data for the overall revenue polarArea chart
    var overallRevenueData = {
        labels: <?php echo $overallRevenueLabelsJSON; ?>,
        datasets: [{
            label: 'Monthly Revenue',
            data: <?php echo $overallRevenueDataJSON; ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
            ],
            borderWidth: 1
        }]
    };

    var ctxOverallRevenue = document.getElementById('overallRevenueChart').getContext('2d');
    var overallRevenueChart = new Chart(ctxOverallRevenue, {
        type: 'polarArea',
        data: overallRevenueData,
        options: {
            scales: {
                r: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


<script>
    var ctxDriversVsOwnersBarChart = document.getElementById('driversVsOwnersBarChart').getContext('2d');
    var driversVsOwnersBarChart = new Chart(ctxDriversVsOwnersBarChart, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($driversVsOwnersLabels); ?>,
            datasets: [{
                label: 'Drivers',
                data: <?php echo json_encode($driversCount); ?>,
                backgroundColor: 'rgb(255, 99, 132)',
            }, {
                label: 'Owners',
                data: <?php echo json_encode($ownersCount); ?>,
                backgroundColor: '#f4762d',
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                },
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
<script>
    // Example data for the monthly bookings bar chart
    var monthlyBookingsData = {
        labels: <?php echo $monthlyBookingsLabelsJSON; ?>,
        datasets: [{
            label: 'Monthly Bookings',
            data: <?php echo $monthlyBookingsDataJSON; ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    var ctxMonthlyBookingsBarChart = document.getElementById('monthlyBookingsBarChart').getContext('2d');
    var monthlyBookingsBarChart = new Chart(ctxMonthlyBookingsBarChart, {
        type: 'bar',
        data: monthlyBookingsData,
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


<!-- footer -->
<?php include '../app/components/footer.php'; ?>