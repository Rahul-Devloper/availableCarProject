<?php

// finding the current page
$currentPage = basename($_SERVER['PHP_SELF']);

// navLink Items
$navLinkItems = [
  'Profile' => 'driverProfile.php',
  'Dashboard' => 'driverDashboard.php',
  'Search Cars' => 'searchCars.php',
];
?>

<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <?php foreach ($navLinkItems as $label => $url) : ?>
    <a class=" <?php echo ($currentPage === $url) ? 'active' : ''; ?>" href="<?php echo $url; ?>"><?php echo $label; ?></a>
  <?php endforeach; ?>
  <a href="#">Logout</a>
</div>