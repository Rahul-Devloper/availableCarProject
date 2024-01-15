<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $message = $_POST["message"];

    // Set recipient email address (replace with your actual email address)
    $to = "rameshrahul26@gmail.com";

    // Set subject
    $subject = "New Contact Us Form Submission";

    // Build the email message
    $email_message = "Name: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Phone: $phone\n";
    $email_message .= "Message:\n$message";

    // Additional headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    $success = mail($to, $subject, $email_message, $headers);

    // Check if the email was sent successfully
    if ($success) {
        echo "<script> alert('Thank you for contacting us! We will get back to you shortly.'); </script>";
    } else {
        echo "<script> alert('Oops! Something went wrong. Please try again.'); </script>";
    }
}
?>


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
                        support@carHavenHire.com
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
                <form class="box-design" id="contactform" action="https://formsubmit.io/send/rameshrahul26@gmail.com" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="name" placeholder="Bob">
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" id="exampleFormControlInput1" name="email" placeholder="qI1p3@example.com">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="phone" placeholder="+44 1234567890">
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="message" rows="3"></textarea>
                    </div>
                    <input name="_formsubmit_id" type="text" style="display:none">
                    <div class="btn-modification d-flex justify-content-center">
                        <button type="submit" class="btn btn-rounded">Submit</button>
                    </div>
                </form>
                <!-- contact form end -->
            </div>
        </div>
    </div>
</section>

<!-- footer -->
<?php include '../app/components/footer.php'; ?>