<?php

// finding the current page
$currentPage = basename($_SERVER['PHP_SELF']);

// navLink Items
$navLinkItems = [
  'Profile' => 'ownerProfile.php',
  'Dashboard' => 'ownerDashboard.php',
  'Add Car' => 'addCar.php',
  'Cars' => 'cars.php',
  'Bookings' => 'ownerBookings.php',
  'Feedback' => 'ownerFeedback.php',
];
?>

<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <?php foreach ($navLinkItems as $label => $url) : ?>
    <a class=" <?php echo ($currentPage === $url) ? 'active' : ''; ?>" href="<?php echo $url; ?>"><?php echo $label; ?></a>
  <?php endforeach; ?>
  <a href="#">Logout</a>
</div>