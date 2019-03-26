<?php
  session_start();
  // var_dump($_SESSION);
  require 'pdo.php';

  $sql = $pdo->prepare('SELECT * FROM org where org_email = :email');
  $sql->execute(array(
    ':email' => $_SESSION['username']
  ));
  $org = $sql->fetch();
  // var_dump($org);

  $sql2 = $pdo->prepare('SELECT * FROM user WHERE org_id = :id');
  $sql2->execute(array(
    ':id' => $org['org_id']
  ));
  $users = $sql2->fetchAll();
  // var_dump($users);

  // $sql3 = $pdo->prepare('SELECT * FROM test WHERE org_id = :id');
  // $sql3->execute(array(
  //   ':id' => $org['org_id']
  // ));
  // $tests = $sql3->fetchAll();
  // var_dump($tests);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ORGANIZATION VIEW</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php
      require 'header.php';
    ?>
    <div class="container">
        <div class="col-8 order-3 m-auto">
          <?php
          if(isset($_SESSION['success'])) {
              echo("<p class='alert alert-success' id='successmsg'> Success: ".htmlentities($_SESSION['success'])."</p>");
              sleep(1);
            }
          ?>
          <ul class='nav nav-tabs my-2'>
            <li class='nav-item'>
              <a href="#" class='nav-link' onclick="$('#profile').toggle()">Profile View</a>
            </li>
          </ul>
          <form class="form border border-dark p-3" method="post" id='profile' style="display: none">
            <h4 class="text-center"><?= $org['long_org_name'] ?> - PROFILE VIEW</h4>
            <p>
              <label for="long">Organization Name:</label>
              <input type="text" name="long_org_name" value="<?= htmlentities($org['long_org_name']) ?>" class='form-control' onclick="disabled=true">
            </p>
            <p>
              <label for="short">Organization Short Code:</label>
              <input type="text" name="short_org_name" value="<?= htmlentities($org['short_org_name']) ?>" class='form-control'>
            </p>
            <p>
              <label for="email">Organization Email/Username:</label>
              <input type="text" name="org_email" value="<?= htmlentities($org['org_email']) ?>" class='form-control' onclick="disabled=true">
            </p>
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $(document).ready(setTimeout(function() {
        $('#successmsg').css('display', 'none')
      }, 2000))

    </script>
  </body>
</html>
