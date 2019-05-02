<?php
  if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    $_SESSION['error'] = "User must be authenticated! Please Login to proceed.";
    die();
  }
?>
