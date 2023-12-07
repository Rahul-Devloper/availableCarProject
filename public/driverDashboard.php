<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php 
    $firstName = '';
    session_start();
    if(isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
        $firstName = $_SESSION['firstName'];
    }
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
            Welcome  <a href="driverProfile.php" class="text-decoration-none" style="color: #0dcaf0;"><?php echo $firstName ?></a>
        </h3>
    </section>
<!-- dashboard end -->

<!-- driverDashboard start -->

<section class="my-5 px-3">
    <div class="row">
        <div class="col-lg-6 d-flex mb-4 justify-content-end">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Search Cars</h5>
                    <a href="cars.php" class="card-link">Search</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex mb-4 justify-content-start">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet.</p>
                    <a href="#" class="card-link">View Owners</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex mb-4 justify-content-end">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue Statistics</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet.</p>
                    <a href="#" class="card-link">View</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex mb-4 justify-content-start">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Overall Rating</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet.</p>
                    <a href="#" class="card-link">View</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- driverDashboard end -->

<!-- footer -->
<?php include '../app/components/footer.php'; ?>