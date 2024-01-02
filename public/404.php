<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>

<section class="container-fluid my-5">
    <h3 class="display-4">
        404
    </h3>
    <?php
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
        echo "<h4>You are not authorized to view this page as you are a " . $role . "</h4>";
    }
    ?>
</section>

<!-- footer -->
<?php include '../app/components/footer.php'; ?>