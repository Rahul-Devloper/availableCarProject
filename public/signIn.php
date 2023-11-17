<!-- connection -->
<?php include '../app/config/connection.php' ?>


<?php

session_start();

$validLogin = NULL;
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role']))
    if ($_SESSION['role'] == 'owner') {
        header("Location: ownerDashboard.php");
        // echo 'owner';
        exit();
    } elseif ($_SESSION['role'] == 'driver') {
        header("Location: driverDashboard.php");
        // echo 'driver';
        exit();
    } elseif ($_SESSION['role'] == 'admin') {
        header("Location: adminDashboard.php");
        // echo 'driver';
        exit();
    }

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "Logged in successfully";
        $row = $result->fetch_assoc();
        $role = $row['role'];
        $firstName = $row['first_name'];
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;
        $_SESSION['firstName'] = $firstName;


        if ($role == 'owner') {
            header("Location: ownerDashboard.php");
            // echo 'owner';
            exit();
        } elseif ($role == 'driver') {
            header("Location: driverDashboard.php");
            // echo 'driver';
            exit();
        } elseif ($role == 'admin') {
            header("Location: adminDashboard.php");
            // echo 'driver';
            exit();
        }
    } else {
        $validLogin = false;
        echo "Invalid username or password";
    }
}
$conn->close();
?>



<?php
// header
include '../app/components/header.php';
?>
<!-- navbar -->
<?php
include '../app/components/navbar.php';
?>

<!-- sign In form -->
<section class="my-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 justify-content-center mx-auto box-design" style="width: 23%;
                height: 50%;
                padding: 2rem;
                margin-top: auto;
                margin-bottom: auto;
                border-radius: 0px;">
                <?php if (isset($validLogin) && $validLogin == false) echo '<p class="alert-style">Invalid username or password</p>'; ?>
                <form method="post" action="signIn.php" style="margin-top: auto;
    margin-bottom: auto;">
                    <div class="col-12 d-flex justify-content-start">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <span class="btn-modification">
                        <button type="submit" name="signIn" class="btn btn-rounded">Sign In</button>
                    </span>
                </form>
                <p>Don't have an account? <a class="text-decoration-none text-primary fw-bold ms-1 me-1 " href="signUp.php">Sign Up</a></p>

            </div>
        </div>
    </div>
</section>

<!-- include footer -->
<?php
include '../app/components/footer.php';
?>