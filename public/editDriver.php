<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
// Check if 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $userId = intval($_GET['id']);

    // Perform the SQL query to fetch the user with the specified ID
    $sql = "SELECT * FROM users WHERE user_id = $userId";
    $result = $conn->query($sql);

    // Check if a user with the specified ID exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Display user details or use them as needed
        echo 'User ID: ' . $user['user_id'] . '<br>';
        echo 'Full Name: ' . $user['first_name'] . ' ' . $user['last_name'] . '<br>';
        echo 'Email: ' . $user['email'] . '<br>';
        echo 'Phone Number: ' . $user['phone_number'] . '<br>';
        echo 'Created At: ' . $user['created_at'] . '<br>';
    } else {
        echo 'User not found.';
    }
} else {
    echo 'Invalid request. User ID not provided.';
}

// Close the MySQL connection
$conn->close();
?>
<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/ownerSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- edit start -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4">
        Edit Driver
    </h3>
</section>
<!-- edit end -->


<!-- profile section -->

<section class="container-fluid my-5">
    <h4 class="display-6 text-center">
        Profile
    </h4>

    <div class="row profile">
        <div class="col-lg-8 d-flex justify-content-center">
            <div class="accordion" id="accordionParent">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            Personal Information
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionParent">
                        <div class="accordion-body">
                            <form action="editDriver.php?id=<?php echo $user['user_id']; ?>" method="POST">
                                <div class="container text-center">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-3">First Name:
                                                <input type="text" class="form-control" name="first_name" value="<?php echo $user['first_name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3">Last Name:
                                                <input type="text" class="form-control" name="last_name" value="<?php echo $user['last_name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3">Email:
                                                <input type="text" class="form-control" name="email" value="<?php echo $user['email']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3">Phone Number:
                                                <input type="text" class="form-control" name="phone_number" value="<?php echo $user['phone_number']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3">Postal Code:
                                                <input type="text" class="form-control" name="postal_code" value="<?php echo $user['postal_code']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 btn-modification d-flex">
                                            <button type="submit" name="update" class="btn btn-rounded mx-auto">Update</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>

                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                            Payment Information
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionParent">
                        <div class="accordion-body">
                            <button type="button" class="btn btn-primary btn-circle"><i style="font-size: x-large;" class="bi bi-plus"></i></button>
                            <div class="card text-center mb-3 mx-auto" style="width: 80%;">
                                <div class="card-body">
                                    <h5 class="card-title">Card Name</h5>
                                    <p class="card-text">Card Number</p>
                                    <a href="#" class="btn btn-outline-primary">Update</a>
                                    <a href="#" class="btn btn-outline-danger">Delete</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                            Cars
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionParent">
                        <div class="accordion-body">
                            <button type="button" class="btn btn-primary btn-circle"><i style="font-size: x-large;" class="bi bi-plus"></i></button>

                            <div class="card mx-auto mb-3" style="width: 80%; max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="../public/assets/img/car-sample.jpg" class="img-fluid rounded" alt="car-image">
                                        <p><small>car number</small></p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">Car Name</h5>
                                            <p class="card-text"><small class="text-body-secondary">Location</small></p>
                                            <a href="#" class="btn btn-outline-primary">Update</a>
                                            <a href="#" class="btn btn-outline-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                            License Details
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionParent">
                        <div class="accordion-body">
                            <div class="card text-center mb-3 mx-auto" style="width: 80%;">
                                <div class="card-body">
                                    <h5 class="card-title">Name: </h5>
                                    <p class="card-text">License Number:</p>
                                    <p class="card-text">Expiry Date:</p>
                                    <a href="#" class="btn btn-outline-primary">Update</a>
                                    <a href="#" class="btn btn-outline-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                            Privacy Policy
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionParent">
                        <div class="accordion-body">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, delectus natus! Natus quis modi ad fuga dicta neque corrupti eveniet minima deleniti perferendis molestias, porro tempore cumque impedit ratione accusamus, excepturi ipsa harum aut dolor, sit beatae atque provident. Nihil, repellendus fuga unde commodi doloremque ea saepe, repudiandae labore non nesciunt quis ratione nobis ex. Reprehenderit neque exercitationem possimus quae quo. Officiis similique amet, voluptatem ratione, quaerat voluptas placeat harum reprehenderit, et voluptates tempora consequatur asperiores! Animi ratione repudiandae neque consectetur omnis mollitia sit autem assumenda, optio veritatis, in nostrum adipisci velit officiis minima, eaque repellat est earum! Molestiae vero sequi consequuntur deleniti eos recusandae voluptas ut at ratione qui. Dolores perspiciatis facere dolorum magni eum voluptas inventore velit. Atque, fuga ab at rem natus nemo non ea dolores libero accusamus quasi corrupti cum veniam architecto labore error, earum rerum dolorum! Natus quidem, veniam harum commodi possimus eligendi quia hic ex dolorem! Accusantium dicta nesciunt quod, porro enim deserunt possimus facere ipsam, consequatur ipsum quasi tempore vero? Delectus, officia perferendis facere labore accusantium obcaecati. Quibusdam aperiam iure earum explicabo officiis nihil accusamus itaque culpa soluta, totam esse veritatis inventore tempore perferendis voluptatem reprehenderit natus similique quaerat obcaecati velit? Illo, atque.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div>
                <img src="../public/assets/img/profile.png" alt="profile picture" class="img-fluid centered-img">
            </div>

            <hr class="w-50 mx-auto">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-right">Phone Number <br><?php echo $user['phone_number']; ?></p>
                </div>
                <div class="col-md-6">
                    <p class="text-left">Email <br><?php echo $user['email']; ?></p>
                </div>
            </div>
            <div class="col-12 btn-modification d-flex">
                <button type="submit" class="btn btn-rounded mx-auto">Log out</button>
            </div>
        </div>

        <!-- Accordian section end -->

    </div>



</section>



<!-- footer -->
<?php include '../app/components/footer.php'; ?>