<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>


<section class="container-fluid my-5">
    <h4 class="display-6 text-center">
        All you have to do is fill in the below form, and we will show you the available cars for you to choose from.
    </h4>

    <div class="row">
        <h4 class="display-6 text-center my-5">
            Rent Cars from your nearby Location!!!
        </h4>
        <div class="col-lg-6 mx-auto my-auto">
            <!-- find cars form -->
            <?php include '../app/components/findCarsForm.php'; ?>
            <!-- find cars form end -->
        </div>
        <div class="col-lg-6">
            <img class="img-fluid" src="../public/assets/img/contact.png" alt="contactImage">
        </div>
    </div>
</section>

<!-- include footer -->
<?php include '../app/components/footer.php'; ?>