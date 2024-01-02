<?php
include '../app/config/connection.php';
session_start();

?>


<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php 
if (isset($_SESSION['role']) && $_SESSION['role'] == 'driver') {
    include '../app/components/driverSidebar.php';
    include '../app/components/sidebarButton.php';
}  else if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    include '../app/components/adminSidebar.php';
    include '../app/components/sidebarButton.php';
} else if(isset($_SESSION['role']) && $_SESSION['role'] == 'owner') {
    include '../app/components/ownerSidebar.php';
    include '../app/components/sidebarButton.php';
}

?>
<!-- Carousel start -->
<section>
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="3000">
                <img src="../public/assets/img/CarouselOne.png" class="d-block w-100 img-fluid" alt="">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="../public/assets/img/carouselTwo.png" class="d-block w-100 img-fluid" alt="">
            </div>
            <!-- <div class="carousel-item" data-bs-interval="3000">
                <img src="../public/assets/img/CarouselOne.png" class="d-block w-100 img-fluid" alt="">
            </div> -->
        </div>
        <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button> -->
    </div>
</section>
<!-- Carousel End -->

<section class="my-5 px-3 container">
    <div class="row">
        <div class="col-lg-6">
            <!-- find cars form -->
            <form method="post" action="findCars.php#carResults" class="box-design" onsubmit="return validateForm()">
                <div class="form-group row mb-3">
                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="location" name="location" placeholder="Enter Postal Code" value="<?php echo isset($_POST['location']) ? $_POST['location'] : ''; ?>" required>
                            <div class="input-group-append">
                                <i class='bx bx-current-location' style="font-size: xx-large; padding: 5px; background: black; color: white" onclick="getCurrentLocation()"></i>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="form-group row">
                    <label for="date1" class="col-sm-2 col-form-label">Date and Time</label>
                    <div class="col-sm-4">
                        <input type="date" name="startDate" class="form-control" id="date1" value="<?php echo isset($_POST['startDate']) ? $_POST['startDate'] : ''; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="startTime" class="form-control" id="time1" value="<?php echo isset($_POST['startTime']) ? $_POST['startTime'] : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date2" class="col-sm-2 col-form-label">Date and Time</label>
                    <div class="col-sm-4">
                        <input type="date" name="endDate" class="form-control" id="date2" value="<?php echo isset($_POST['endDate']) ? $_POST['endDate'] : ''; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="time" name="endTime" class="form-control" id="time2" value="<?php echo isset($_POST['endTime']) ? $_POST['endTime'] : ''; ?>" required>
                    </div>
                </div>
                <div id="locationFormError" class="text-center ">
                    <!-- filled by javascript validation -->
                </div>

                <!-- Submit button to add a car -->
                <div class="col-12 btn-modification">
                    <button type="submit" name="find_car" class="btn btn-rounded">Find Car</button>
                </div>
            </form>
            <!-- find cars form end -->
        </div>
        <div class="col-lg-6">
            <h6 class="display-5 text-center">
                Fill in and Find cars at your convenience!!
            </h6>
            <br>
            <p class="text-center">
                Enter the locatino and date and time and we will show you the available cars for you to choose from.
            </p>
        </div>
    </div>
</section>

<!-- Why us Section start-->
<section class="my-5 px-3 container" data-aos="fade-up">
    <div class="container" id="features">
        <h5 class="display-5 text-center">Why Us?</h5>

        <div class="row mt-4">
            <div class="col-sm features-box">
                <div>
                    <i class='bx bxs-coffee-bean' style="font-size: 3rem;"></i>
                    <h5>Reliable</h5>
                </div>
            </div>
            <div class="col-sm features-box">
                <div>
                    <i class="bi bi-person-check-fill" style="font-size: 2.5rem;"></i>
                    <h5>Prefessional</h5>
                </div>
            </div>
            <div class="col-sm features-box">
                <div>
                    <i class="bx bxs-award" style="font-size: 3rem;"></i>
                    <h5>Credible</h5>
                </div>
            </div>
            <div class="col-sm features-box">
                <div>
                    <i class='bx bx-show' style="font-size: 3rem;"></i>
                    <h5>Transparent</h5>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why us Section end -->
<section class="mt-5 px-3 container" data-aos="fade-left">
    <div class="row d-flex align-items-center">
        <h6 class="display-5 text-center">
            What you Experience as Rider!!!
        </h6>
        <div class="col-lg-6">
            <br>
            <p class="font-design" style="font-size: large; font-weight: 500;">
                <i class='bx bx-chevrons-right'></i>Endless Possibilities with our diverse fleet of vehicles
                <br>
                <i class='bx bx-chevrons-right'></i>Simplified Booking process to hit the road in no time
                <br>
                <i class='bx bx-chevrons-right'></i>Safe and Secure Payments
                <br>
                <i class='bx bx-chevrons-right'></i>We are always available 24/7 for you
            </p>
            <br>
            <span class="btn-modification text-center mt-4">
                <a href="findCars.php" class="btn btn-rounded">Find Cars</a>
            </span>
        </div>
        <div class="col-lg-6">
            <img class="img-fluid" src="../public/assets/img/rider.png" alt="rider">
        </div>
    </div>

    <div class="row d-flex align-items-center" data-aos="fade-right">
        <h6 class="display-5 text-center">
            What you Experience as an Owner!!!
        </h6>
        <div class="col-lg-6">
            <img class="img-fluid" src="../public/assets/img/owner.png" alt="owner">
        </div>
        <div class="col-lg-6">
            <br>
            <p class="font-design" style="font-size: large; font-weight: 500;">
                <i class='bx bx-chevrons-right'></i>Turn your car into a cash machine
                <br>
                <i class='bx bx-chevrons-right'></i>Sit back and Earn while you are at home. Your car does all the work
                <br>
                <i class='bx bx-chevrons-right'></i>Flexible Scheules
                <br>
                <i class='bx bx-chevrons-right'></i>We are always available 24/7 for you
            </p>
            <br>
            <span class="btn-modification text-center mt-4">
                <a href="addCar.php" class="btn btn-rounded">List you car</a>
            </span>
        </div>

    </div>

</section>
<!--  -->

<!-- testimonials start -->
<!-- <section class="my-5 p-3 container">
    <div class="container">
        <h5 class="display-5 text-center">Check Out Some Of our Client Testimonials</h5>
        <div id="carouselExampleFade" class="carousel slide carousel-fade mt-4">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="card mb-3 mx-auto" style="width: 75%; height: 15rem;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="../public/assets/img/profile.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">John Doe</h5>
                                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card mb-3 mx-auto" style="width: 75%; height: 15rem;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="../public/assets/img/profile.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Glenn Max</h5>
                                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card mb-3 mx-auto" style="width: 75%; height: 15rem;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="../public/assets/img/profile.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Jasmin Tasha</h5>
                                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- testimonials end -->

<!-- register start -->

<section class=" px-3 container">
    <div class="row">
        <div class="col-lg-6">
            <img class="img-fluid" src="../public/assets/img/carOwner.png" alt="carOwner">
        </div>
        <div class="col-lg-6 mx-auto my-auto">
            <p class="font-design">
                Rev up your ride and unlock the road to extra cash! If you've got a set of wheels, why not flip the script and go from car owner to cash flow connoisseur? Join our driver community today and turn your car into a cash-making superstar. Your ride, your rules, your side hustle – it's time to hit the road and earn while you cruise!
            </p>
            <span class="btn-modification text-center mt-4">
                <a href="signUp.php" class="btn  btn-rounded">Register Today</a>
            </span>
        </div>
</section>
<!-- register end -->

<!-- footer start -->
<?php include '../app/components/footer.php'; ?>
<!-- footer end -->