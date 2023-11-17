<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
        $firstName = '';
        session_start();
        if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
            $firstName = $_SESSION['firstName'];
        }
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
        Welcome Admin
    </h3>
</section>
<!-- dashboard end -->

<!-- adminDashboard start -->

<section class="my-5 px-3">
    <div class="row">
        <div class="col-lg-6 d-flex justify-content-end">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Drivers</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet.</p>
                    <a href="drivers.php" class="card-link">View Drivers</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex justify-content-start">
            <div class="card" style="width: 75%;">
                <div class="card-body">
                    <h5 class="card-title">Owners</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet.</p>
                    <a href="#" class="card-link">View Owners</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- adminDashboard end -->

<!-- footer -->
<?php include '../app/components/footer.php'; ?>