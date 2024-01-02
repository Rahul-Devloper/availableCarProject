<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
session_start();
$role = $_SESSION['role'];
$email = $_SESSION['email'];
$firstName = $_SESSION['firstName'];
$userId = $_SESSION['userId'];
?>

<!-- check if update button is clicked -->
<?php
if (isset($_POST['update']) && $userId) {
    $userId = $userId;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $postal_code = $_POST['postal_code'];

    $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone_number = '$phone_number', postal_code = '$postal_code' WHERE user_id = $userId";
    $result = $conn->query($sql);
    if ($result) {
        // echo "User updated successfully";
        echo '<script>alert("User updated successfully");</script>';
    } else {
        // echo "Error updating user: " . $conn->error;
        echo '<script>alert("Error updating user: ' . $conn->error . '");</script>';
    }
}
?>

<?php
// start session to get email, firstname and role


// Check if 'id' parameter exists in the URL
if ($role && $email && $firstName) {
    // Sanitize the input to prevent SQL injection
    $userId = $userId;

    // Perform the SQL query to fetch the user with the specified ID
    $sql = "SELECT * FROM users WHERE user_id = $userId";
    $result = $conn->query($sql);

    // Check if a user with the specified ID exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // echo 'User not found.';
        echo '<script>alert("User not found.");</script>';
    }
} else {
    // echo 'Invalid request. User ID not provided.';
    echo '<script>alert("Invalid request. User ID not provided.");</script>';
}

// upload image
if (isset($_FILES["user_image"]["name"]) && isset($_FILES["user_image"]["tmp_name"]) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['user_image_update'])) {
    // echo 'upload image';
    $delteImageSql = "SELECT user_image_name FROM users WHERE user_id = $userId";
    $result = $conn->query($delteImageSql);
    $row = $result->fetch_assoc();
    $oldImageName = $row['user_image_name'];


    $target_dir = "assets/img/profileUploads/";
    $user_image_name = $userId . "_" . basename($_FILES["user_image"]["name"]);
    $target_file = $target_dir . $userId . "_" . basename($_FILES["user_image"]["name"]);
    // echo $target_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["user_image"]["tmp_name"]);
    if ($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        // echo "File is not an image.";
        // display an alert message
        echo '<script>alert("File is not an image.");</script>';
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // echo "Sorry, file already exists.";
        echo '<script>alert("Sorry, file already exists.");</script>';
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["user_image"]["size"] > 50000000) {
        // echo "Sorry, your file is too large.";
        echo '<script>alert("Sorry, your file is too large.");</script>';
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");</script>';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // echo "Sorry, your file was not uploaded.";
        echo '<script>alert("Sorry, your file was not uploaded.");</script>';
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
            // echo "The file " . htmlspecialchars(basename($_FILES["user_image"]["name"])) . " has been uploaded.";
            echo '<script>alert("The file ' . htmlspecialchars(basename($_FILES["user_image"]["name"])) . ' has been uploaded.");</script>';
        } else {
            // echo "Sorry, there was an error uploading your file.";
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
        }
    }

    $sql = "UPDATE users SET user_image_name = '$user_image_name' WHERE user_id = $userId";
    $result = $conn->query($sql);

    // delete old image from directory
    // if($oldImageName != null)unlink("assets/img/profileUploads/" . $oldImageName);

    //refresh page after update
    header("Location: ../public/driverProfile.php");
}


// Close the MySQL connection
$conn->close();
?>


<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php
include '../app/components/navbar.php'; ?>

<!-- sidebar -->
<?php include '../app/components/driverSidebar.php'; ?>

<!-- sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>


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
                            <form action="driverProfile.php" method="POST">
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
            <div class="col-12 btn-modification d-flex mx-auto justify-content-center">
                <button type="button" class="btn btn-rounded" data-bs-toggle="modal" data-bs-target="#imageModal">
                    Upload Profile Picture
                </button>
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


<!-- image upload modal -->
<div class="modal fade" id="imageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update image</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="driverProfile.php" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <label for="image" class="form-label"> Upload Image</label>
                    <input class="form-control" type="file" id="image" name="user_image">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="user_image_update" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- footer -->
<?php
include '../app/components/footer.php'; ?>