<?php
session_set_cookie_params(18000);
session_start();
require "unauthorized.php";
  require 'pdo.php';

  $sql1 = $pdo->prepare("SELECT name, email, org_id FROM user where email = :email");
  $sql1->execute(array(
    ':email' => $_SESSION['login']
  ));
  $user = $sql1->fetch();

  $query = $pdo->prepare("SELECT * FROM test where org_id = :o_id");
  $query->execute(array(
    ':o_id' => $user['org_id']
  ));
  $tests = $query->fetchAll(PDO::FETCH_ASSOC);

  $totqs = $pdo->query("SELECT COUNT(question_id) AS total_questions FROM test_questions");
  $question_count = $totqs->fetch();
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
          <div id="testdiv" class="table-responsive">
            <table class="table table-bordered table-stripped">
              <thead>
                <tr>
                  <th>Test ID</th>
                  <th>Test Title</th>
                  <th>No. of questions</th>
                  <th>Test Duration</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($tests as $test) {
                    echo("<tr>");
                    echo("<th>".$test['test_id']."</th>");
                    echo("<td>".$test['title']."</td>");
                    echo("<td>".$question_count['total_questions']."</td>");
                    echo("<td>".$question_count['total_questions']." minutes</td>");
                    echo("<td><a href='testpage.php?org=".urlencode($user['org_id'])."&user=".urlencode($_GET['user'])."&test=".urlencode($test['test_id'])."'>View/Take Test</a>");
                    echo("</tr>");
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#navlink').html("Logout");
      $("#navlink").on('click', function() {
        confirm("Do you want proceed with log out?")
      });
      $("#navlink").attr("href", "logout.php");
    </script>
  </body>
</html>
