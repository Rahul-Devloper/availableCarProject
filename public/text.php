<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<!-- include header -->
<?php include '../app/components/header.php'; ?>


<!-- include navbar -->
<?php include '../app/components/navbar.php'; ?>

<section>
<div class="col-md-6">
    <label for="carAvailability">Car Availability Type:</label>
    <span style="color: black;"><?php echo $car['car_availability_type'] == 'always' ? 'Always Available' : 'Specific Days and Times'; ?></span>
    <?php if ($car['car_availability_type'] == 'date_time') : ?>
        <input type="checkbox" name="always_available" id="alwaysAvailable" <?php echo $car['always_available'] ? 'checked' : ''; ?>> Always Available
    <?php endif; ?>
    <button type="button" class="btn btn-primary rounded-pill mt-2 me-2" data-bs-toggle="modal" data-bs-target="#availabilityModal" <?php echo $car['always_available'] ? 'disabled' : ''; ?>>
        Change Schedule
    </button>
</div>

</section>



<!-- footer -->
<?php include '../app/components/footer.php'; ?>