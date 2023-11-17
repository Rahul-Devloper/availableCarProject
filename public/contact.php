<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<section class="my-5">
    <div class="container-fluid">
        <div>
            <p class="text-center">
                We are always available 24/7 for you!. Feel free to contact us
            </p>
        </div>
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="col-lg-3 text-center flat-box-design">
                    <i class="bi bi-phone-fill" style="font-size: 4rem;"></i>
                    <p>+44 1234567890</p>
                </div>
                <div class="col-lg-3 text-center flat-box-design">
                    <i class="bi bi-geo-alt-fill" style="font-size: 4rem;"></i>
                    <p>
                        12, middle street, london
                    </p>
                </div>
                <div class="col-lg-3 text-center flat-box-design">
                    <i class="bi bi-envelope-fill" style="font-size: 4rem;"></i>
                    <p>
                        availablecars@mail.com
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <h4 class="display-6 text-center my-5">
                Contact Us!
            </h4>
            <div class="col-lg-6">
                <img class="img-fluid" src="../public/assets/img/contact.png" alt="contactImage">
            </div>
            <div class="col-lg-6 mx-auto my-auto">
                <!-- contact form -->
                <form class="box-design">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Bob">
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="qI1p3@example.com">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="+44 1234567890">
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <div class="btn-modification d-flex justify-content-center">
                        <a class="btn btn-rounded" href="#">Submit</a>
                    </div>
                </form>
                <!-- contact form end -->
            </div>
        </div>
    </div>
</section>

<!-- footer -->
<?php include '../app/components/footer.php'; ?>