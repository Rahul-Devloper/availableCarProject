<?php

if (session_status() == PHP_SESSION_NONE) {
  // If not, start a new session
  session_start();
}
$firstName = '';
$sessionLogin = NULL;
if (isset($_SESSION['email']) && isset($_SESSION['firstName']) && isset($_SESSION['role'])) {
  $sessionLogin = true;
  $firstName = $_SESSION['firstName'];
}

// finding the current page
$currentPage = basename($_SERVER['PHP_SELF']);



// navLink Items
$navLinkItems = [
  'Home' => 'index.php',
  'About' => 'about.php',
  'Services' => 'services.php',
  'Contact' => 'contact.php',
  'Find Cars' => 'findCars.php',
  'Add Cars' => 'addCar.php',
];
?>


<nav class="navbar navbar-expand-lg justify-content-center nav-bg">
  <div class="container-fluid">
    <a class="navbar-brand text-overline" style="text-decoration: overline; font-weight: bold; font-size: 2rem;color: #059DC0;" href="#">AC.</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto">
        <?php foreach ($navLinkItems as $label => $url) : ?>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === $url) ? 'active' : ''; ?>" href="<?php echo $url; ?>"><?php echo $label; ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <!-- <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="findCars.php">Find Cars</a>
                </li>
            </ul> -->

            <!-- if user is logged in, navbar will show logout option else show sign in and sign up -->
      <div class="d-flex align-items-center justify-content-end btn-modification">
      <?php if (isset($sessionLogin) && $sessionLogin == false || !isset($sessionLogin)) echo '<a class="btn btn-outline-primary btn-rounded mx-1" href="signIn.php">Sign In</a>
        <a class="btn btn-outline-primary btn-rounded" href="signUp.php">Sign Up</a>'; 
        
        elseif (isset($sessionLogin) && $sessionLogin == true) echo '<div class="dropdown me-2">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="bi bi-person-fill"></i> <?php echo $firstName ?>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <!-- <a class="dropdown-item" href="#">Profile</a>
    <a class="dropdown-item" href="#">Settings</a> -->
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </div>';
        
        ?>

        


    </div>
  </div>
</nav>


<!-- <nav class="navbar navbar-expand-lg justify-content-center nav-bg">
  <div class="container-fluid">
  <a class="navbar-brand text-overline" style="text-decoration: overline; font-weight: bold; font-size: 2rem;color: #059DC0;" href="#">AC.</a>

<ul class="navbar-nav mx-auto">
  <li class="nav-item">
    <a class="nav-link active" href="#">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Services</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">About Us</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Contact</a>
  </li>
</ul>

<div class="d-flex btn-modification">
  <a class="btn  btn-outline-primary me-2 btn-rounded" href="signIn.php">Sign In</a>
  <a class="btn btn-outline-primary btn-rounded" href="signUp.php">Sign Up</a>
</div>
  </div>
</nav> -->