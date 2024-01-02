<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php

session_start();
$role = $_SESSION['role'];
?>

<!-- check if adminSubmit is clicked -->
<?php
if (isset($_POST['adminSubmit']) || isset($_POST['adminSubmitCars'])) {
    //    store adminSelectedUserId in Session
    $_SESSION['adminSelectedUserId'] = intval($_GET['id']);

    //    stop session
    session_write_close();

    if(isset($_POST['adminSubmit'])){
        header("Location: addCar.php");
    }
    else {
        header("Location: cars.php");
    }
} ?>

<!-- check if update button is clicked -->
<?php
if (isset($_POST['update'])) {
    $userId = intval($_GET['id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $postal_code = $_POST['postal_code'];

    $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone_number = '$phone_number', postal_code = '$postal_code' WHERE user_id = $userId";
    $result = $conn->query($sql);
    if ($result) {
        // echo "User updated successfully";
        // display success message
        echo '<script>alert("User updated successfully");</script>';
    } else {
        // echo "Error updating user: " . $conn->error;
        // display error message
        echo '<script>alert("Error updating user: ' . $conn->error . '");</script>';
    }
}
?>
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

        $totalCarsSql = "SELECT * FROM cars WHERE user_id = $userId";
        $totalCarsResult = $conn->query($totalCarsSql);
    } else {
        // echo 'User not found.';
        echo '<script>alert("User not found.");</script>';
    }
} else {
    // echo 'Invalid request. User ID not provided.';
    echo '<script>alert("Invalid request. User ID not provided.");</script>';
}

// Close the MySQL connection
$conn->close();
?>
<!-- include header -->
<?php include '../app/components/header.php'; ?>

<!-- include navebar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/adminSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>

<!-- edit start -->
<section class="my-5 px-3 container-fluid">
    <h3 class="display-4">
        Edit Owner
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
                                                <input type="text" class="form-control" name="email" readonly value="<?php echo $user['email']; ?>">
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
                <?php if ($role === "owner") { //payment info will not be shown for admin
                    // Display the accordion item for the driver role
                ?>
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
                                        <h5 class="card-title">Payment Information</h5>
                                        <p class="card-text">Card Number</p>
                                        <a href="#" class="btn btn-outline-primary">Update</a>
                                        <a href="#" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } // end if
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                            Cars
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionParent">
                        <div class="accordion-body">
                            <button type="button" class="btn btn-primary btn-circle" data-bs-toggle="modal" data-bs-target="#userSearchModal"><i style="font-size: x-large;" class="bi bi-plus"></i></button>

                            <div class="card mx-auto mb-3" style="width: 80%; max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="../public/assets/img/car-sample.jpg" class="img-fluid rounded" alt="car-image">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">Number of Cars</h5>
                                            <p class="card-text"><small class="text-body-secondary"><?php echo $firstName . " has " . $totalCarsResult->num_rows . " cars" ?></small></p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userCarsModal">View Cars</button>
                                        </div>
                                    </div>
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
                <?php
                $userImage = $user['user_image_name'];
                $imagePath = "../public/assets/img/profileUploads/$userImage";

                if (file_exists($imagePath) && $userImage) {
                    // If user image exists, show it
                    echo "<img src=\"$imagePath\" alt=\"profile picture\" class=\"img-fluid centered-img\">";
                } else {
                    // If user image doesn't exist, show a default image
                    echo "<img src=\"../public/assets/img/profile.png\" alt=\"default profile picture\" class=\"img-fluid centered-img\">";
                }
                ?>
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
        </div>

        <!-- Accordian section end -->

    </div>



</section>

<!-- modals to confirm details -->
<div class="modal fade" id="userSearchModal" tabindex="-1" aria-labelledby="userSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userSearchModalLabel">Confirm Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search inputs -->
                <form action="editOwner.php?id=<?php echo $user['user_id']; ?>" method="POST" id="userSearchForm">
                    <div class="mb-3">
                        <label for="email">Email: <?php echo $user['email']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="firstName">First Name: <?php echo $user['first_name']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="role">Role: <?php echo $user['role']; ?></label>
                    </div>
                    <button type="submit" name='adminSubmit' class="btn btn-primary">Confirm details</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="userCarsModal" tabindex="-1" aria-labelledby="userCarsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userCarsModalLabel">Confirm Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search inputs -->
                <form action="editOwner.php?id=<?php echo $user['user_id']; ?>" method="POST" id="userSearchForm">
                    <div class="mb-3">
                        <label for="email">Email: <?php echo $user['email']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="firstName">First Name: <?php echo $user['first_name']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="role">Role: <?php echo $user['role']; ?></label>
                    </div>
                    <button type="submit" name='adminSubmitCars' class="btn btn-primary">Confirm details</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modals to confirm details end -->




<!-- footer -->
<?php include '../app/components/footer.php'; ?>