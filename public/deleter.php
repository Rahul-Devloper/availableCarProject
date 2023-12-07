<?php include '../app/config/connection.php' ?>
<!-- include header -->
<?php include '../app/components/header.php'; ?>

<?php
// deleteCar.php

if (isset($_GET['cars'])) {


    // Capture the values from the query string
    $car_id = $_GET['cars'];

    // Get image name
    $sql = "SELECT car_image_name FROM cars WHERE car_id = $car_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $car_image_name = $row['car_image_name'];

    // Delete image from folder
    $image_path = "../public/assets/img/uploads/$car_image_name";
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    $sql = "DELETE FROM cars WHERE car_id = $car_id";

    if ($conn->query($sql) === TRUE) {
        echo "<h5 class='text-success'>Record deleted successfully</h5>";
        header('Refresh: 2; URL = cars.php');
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect or handle the case when 'cars' parameter is not set
    header("Location: some_error_page.php");
    exit();
}
?>


<!-- include footer -->
<?php include '../app/components/footer.php'; ?>