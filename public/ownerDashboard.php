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
<?php include '../app/components/ownerSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- dashbard start -->
    <section class="my-5 px-3 container-fluid">
        <h3 class="display-4">
            Welcome <?php echo $firstName ?>
        </h3>
    </section>
<!-- dashboard end -->

<!-- footer -->
<?php include '../app/components/footer.php'; ?>