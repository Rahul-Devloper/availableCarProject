<!-- include connection -->
<?php include '../app/config/connection.php' ?>

<?php
// Check if a search query is submitted
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql = "SELECT first_name, last_name, email, phone_number, created_at, user_id, user_image_name FROM users WHERE role = 'owner' AND first_name LIKE '%$searchTerm%'";
} else {
    $sql = "SELECT first_name, last_name, email, phone_number, created_at, user_id, user_image_name FROM users WHERE role = 'owner'";
}
$result = $conn->query($sql);

// Initialize an empty array to store the results
$ownerArray = [];

// Fetch all rows and store them in the array
while ($row = $result->fetch_assoc()) {
    $ownerArray[] = [
        'first_name' => $row['first_name'],
        'last_name' => $row['last_name'],
        'email' => $row['email'],
        'phone_number' => $row['phone_number'],
        'created_at' => $row['created_at'],
        'user_id' => $row['user_id'],
        'user_image_name' => $row['user_image_name'],
    ];
}

// Close the MySQL connection
$conn->close();
?>

<!-- header -->
<?php include '../app/components/header.php'; ?>
<!-- navbar -->
<?php include '../app/components/navbar.php'; ?>

<!-- include sidebar -->
<?php include '../app/components/adminSidebar.php'; ?>


<!-- include sidebar button -->
<?php include '../app/components/sidebarButton.php'; ?>



<section class="my-5 px-3 container-fluid">
    <h3 class="display-4">
        Owners
    </h3>
</section>

<!-- HTML form for search -->
<form action="" method="GET">
    <label for="search">Search by First Name:</label>
    <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <span class="btn-modification">
        <button class="btn btn-rounded" type="submit">Search</button>
    </span>
</form>

<?php
if ($ownerArray == []) {
    echo '        <h4 class="display-4">No Owners found</h4>';
} else {
    echo '<section class="my-5 px-3 container-fluid">';
    echo '<div class="row row-cols-1 row-cols-md-3 g-4">';

    foreach ($ownerArray as $owner) {
        $userImage = $owner['user_image_name'];
        $imagePath = "../public/assets/img/profileUploads/$userImage";
        echo '<div class="col">';
        echo '<div class="card mb-3" style="max-width: 540px;">';
        echo '  <div class="row g-0">';
        echo '    <div class="col-md-4 d-flex align-items-center justify-content-center" style="background: #f4efef;">';
        if (file_exists($imagePath) && $userImage) {
            echo '      <img src="../public/assets/img/profileUploads/' . htmlspecialchars($userImage) . '" class="img-fluid rounded" alt="profile-image">';
        } else {
            echo '      <img src="../public/assets/img/profile.png" class="img-fluid rounded" alt="default-profile-image">';
        }
        echo '    </div>';
        echo '    <div class="col-md-8">';
        echo '      <div class="card-body">';
        echo '        <h5 class="card-title">First Name: ' . htmlspecialchars($owner['first_name']) . '</h5>';
        echo '        <h5 class="card-title">Last Name: ' . htmlspecialchars($owner['last_name']) . '</h5>';
        echo '        <h5 class="card-title">Email: ' . htmlspecialchars($owner['email']) . '</h5>';
        echo '        <p class="card-text"><small class="text-body-secondary">Joined on: ' . htmlspecialchars($owner['created_at']) . '</small></p>';
        echo '      </div>';
        echo '<div class="d-flex justify-content-center">';
        echo ' <a href="editOwner.php?id=' . $owner['user_id'] . '" class="btn btn-rounded btn-primary my-3" style="margin-right: 10px; width: 30%;">Edit</a>';
        echo ' <a href="deleteOwner.php?id=' . $owner['user_id'] . '" class="btn btn-rounded btn-danger my-3" style="width: 30%;">Delete</a>';
        echo ' </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';

        echo '</div>';
    }

    echo '</div>';
    echo '</section>';
}
?>


<!-- footer -->
<?php include '../app/components/footer.php'; ?>