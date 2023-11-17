<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php include '../app/components/ownerSidebar.php'; ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- The sidebar -->
<section id = 'content-wrapper' class="my-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="container content w-85">
                <div>
                    <!-- Add car form (to be implemented) -->
                    <h3 class="text-center display-6">Add Your Car</h3>
                    <form class="box-design mx-auto text-color" style="width: 70%;">
                        <!-- Car details input fields -->
                        <div class="form-group">
                            <label for="carMake">Car Make:</label>
                            <input type="text" class="form-control" id="carMake">

                            <label for="carMake">Car Registration:</label>
                            <input type="text" class="form-control" id="carMake">

                            <label for="carMake">Insurance Number:</label>
                            <input type="text" class="form-control" id="carMake">

                            <label for="carMake">Car Make:</label>
                            <input type="text" class="form-control" id="carMake">

                            <label for="carMake">License Number:</label>
                            <input type="text" class="form-control" id="carMake">

                            <!-- form for adding image -->
                            <label for="carMake">Car Image: (Only JPEG/JPG/PNG)</label>
                            <input type="file" class="form-control" id="carMake">
                            <br />
                        </div>
                        <!-- Other car details input fields -->
                        <!-- Submit button to add a car -->
                        <span class='btn-modification'><button type="submit" class="btn btn-rounded">Add Car</button></span>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <h3 class="display-5 mt-5">
                Add and Proceed....
            </h3>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, optio in? Amet vitae fugiat voluptatibus accusamus perferendis dolores reiciendis nam!
            </p>
        </div>

    </div>
</section>




<?php include '../app/components/footer.php'; ?>