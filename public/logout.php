<?php
   if (session_status() == PHP_SESSION_NONE) {
    // If not, start a new session
    session_start();
  }
   unset($_SESSION["email"]);
   unset($_SESSION["password"]);
   unset($_SESSION["firstName"]);
   unset($_SESSION["adminSelectedUserId"]);
   
   header('Refresh: 2; URL = index.php');
?>