<!-- sign up page for driver and owner with radio button for driver and owner all with bootstrap -->

<!-- connection -->
<?php include '../app/config/connection.php' ?>

<?php

$nonEmpty = ["role", "first_name", "last_name", "email", "password",  "phone_number", "postal_code"];
$inputVars = ["role" => '', "first_name" => '', "last_name" => '', "email" => '', "password" => '',  "phone_number" => '', "postal_code" => ''];
$errors = ["email" => '', "password" => '',  "phone_number" => ''];

// print_r($_POST);
if (isset($_POST['signUp'])) {
    //check for null values
    foreach ($nonEmpty as $field) {
        $inputVars[$field] = $_POST[$field];
        if (empty($_POST[$field])) {
            $errors[$field] = "Error: $field cannot be empty<br>";
        }
        if ($field == "email" && !empty($_POST['email'])) {
            // validateEmail
            $email = $_POST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] =  "Error: invalid email<br>";
            }
        } else if ($field == "password" && !empty($_POST['password'])) {
            // validatePassword
            $password = $_POST['password'];
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $password)) {
                $errors['password'] = "Error: Password must be atleast 8 characters long and must contain at least one uppercase letter, one lowercase letter, one number and one special character<br>";
            }
        } else if ($field == "phone_number" && !empty($_POST['phone_number'])) {
            // validatePhoneNumber along with country code with +44
            $phone_number = $_POST['phone_number'];
            if (!preg_match('/^[+][0-9]{2}[0-9]{10}$/', $phone_number)) {
                $errors['phone_number'] = "Error: Phone number must be a valid phone number with country code<br>";
            }
        }
    }
}


if (array_filter($errors)) {
} else if (isset($_POST['signUp']) && !array_filter($errors)) {
    // insert into users table
    //protecting speacial characters from being inserted in the database
    // $email = mysqli_real_escape_string($connect, $_POST['email']);
    // $password = mysqli_real_escape_string($connect, $_POST['password']);
    // $phone_number = mysqli_real_escape_string($connect, $_POST['phone_number']);

    $role = $_POST['role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = $_POST['phone_number'];
    $postal_code = $_POST['postal_code'];

    $sql = "INSERT INTO users (first_name, last_name, email, password, phone_number, postal_code, role) VALUES ('$first_name', '$last_name', '$email', '$password', '$phone_number', '$postal_code', '$role')";
    if ($conn->query($sql) === TRUE) {
        // echo "New record created successfully";
        header("Location: ../public/successfuleRegistration.php");
    } else {
        // echo "Error: " . $sql . "<br>" . $conn->error;
        // display an alert message
        echo '<script>alert("Error: ' . $conn->error . '")</script>';
    }
}
$conn->close();

?>


<!-- post the form data to the database -->




<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- form for registration for driver and owner -->
<section class="my-5">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center" style="flex-direction: column">
            <div class="col-lg-6 mx-auto box-design shadow" style="width: 40%; border-radius: 0px;">
                <p class="alert-style">
                    Note: <i class="bi bi-exclamation-triangle-fill"></i> Only One account can be used to register for driver or owner.
                </p>
                <form method="post" action="signUp.php" class="row g-3">
                    <div class="col-12 d-flex justify-content-start">
                        <div class="form-check m-1">
                            <input class="form-check-input" type="radio" name="role" id="driver" value="driver" <?php if ($inputVars['role'] === 'driver') {
                                                                                                                    echo 'checked';
                                                                                                                } else echo 'checked'; ?>>
                            <label class="form-check-label" for="driver">
                                Driver
                            </label>
                        </div>
                        <div class="form-check m-1">
                            <input class="form-check-input" type="radio" name="role" id="owner" value="owner" <?php if ($inputVars['role'] === 'owner') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>>
                            <label class="form-check-label" for="owner">
                                Owner
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control" required value="<?php echo htmlspecialchars($inputVars['first_name']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" required value="<?php echo htmlspecialchars($inputVars['last_name']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($inputVars['email']); ?>">
                        <?php if ($errors['email']) echo "<div class='red-text'>$errors[email]</div>"; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required value="<?php echo htmlspecialchars($inputVars['password']); ?>">
                        <?php if ($errors['password']) echo "<div class='red-text'>$errors[password]</div>"; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="+441234567890" required value="<?php echo htmlspecialchars($inputVars['phone_number']); ?>">
                        <?php if ($errors['phone_number']) echo "<div class='red-text'>$errors[phone_number]</div>"; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Postal Code *</label>
                        <input type="text" name="postal_code" class="form-control" required value="<?php echo htmlspecialchars($inputVars['postal_code']); ?>">
                    </div>


                    <div class="col-12 btn-modification">
                        <button type="submit" name="signUp" class="btn btn-rounded">Sign Up</button>
                    </div>
                </form>

                <!-- link for going to signIn -->
                <p>Already have an account? <a class="text-decoration-none text-primary fw-bold ms-1 me-1 " href="signIn.php">Sign In</a></p>
            </div>
            <div class="col-lg-6"></div>
        </div>


    </div>
</section>


<!-- end of form -->

<?php include '../app/components/footer.php'; ?>