<?php
session_start();
  require 'pdo.php';

  $sql = $pdo->query("SELECT * FROM question where category = 'HRPP' ORDER BY RAND() LIMIT 2");
  $questions = $sql->fetch();
  $myj  = JSON_encode($questions);

  $sql1 = $pdo->prepare("SELECT name, email FROM user where email = :email");
  $sql1->execute(array(
    ':email' => $_SESSION['login']
  ));
  $user = $sql1->fetch();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>USER PAGE</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php require 'header.php'?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-8 order-3 m-auto border border-dark p-5">
          <h4 class="text-center"> Welcome, <?= htmlentities($user['name']) ?></h4>
          <p class="float-right text-info small"><strong> <?= "Date: ". date("d M Y") ?></strong></p>
          <ul class="nav nav-tabs my-5">
            <li class="nav-item mx-auto"><a href="#" class="nav-link">Profile</a></li>
            <li class="nav-item mx-auto"><a href="#" class="nav-link">Available Tests</a></li>
            <li class="nav-item mx-auto"><a href="#" class="nav-link">Test History</a></li>
          </ul>
        </div>
      </div>
    </div>
  </body>
</html>
