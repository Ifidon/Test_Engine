<?php
  session_start();
  // var_dump($_SESSION);
  require 'pdo.php';

  $sql = $pdo->prepare('SELECT * FROM org where org_email = :email');
  $sql->execute(array(
    ':email' => $_SESSION['login']
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
      <div vlass="row">
          <div class="col-8 order-3 m-auto">
            <?php
            if(isset($_SESSION['success'])) {
                echo("<p class='alert alert-success' id='successmsg'> Success: ".htmlentities($_SESSION['success'])."</p>");
                sleep(1);
              }
            ?>
            <ul class='nav nav-tabs my-2'>
              <li class='nav-item'>
                <a href="#" class='nav-link' id='plink'>Profile View</a>
              </li>
              <li class='nav-item'>
                <a href="#" class='nav-link' id='ulink'>Users</a>
              </li>
              <li class='nav-item'>
                <a href="#" class='nav-link' id='qalink'>Questions</a>
              </li>
            </ul>
            <form class="form border border-dark p-3" method="post" id='profile'>
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
          <div class="table-group border border-dark p-3" id='userlist' style="display:none">
            <h4 class='text-center mb-3'>LIST OF REGISTERED USERS FOR <strong><?= $_SESSION['org'] ?></strong></h4>
            <table class='table table-light'>
              <thead>
                <tr>
                  <th>User ID</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Phone No</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($users as $user) {
                    echo("<th scope='row'>".$user['user_id']."</th>");
                    echo("<td>".$user['name']."</td>");
                    echo("<td>".$user['email']."</td>");
                    echo("<td>No phone added</td>");
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="for-group border border-dark p-3" id="questionadd" style="display:none">
            <form class="form" method="post">
              <p>
                <label for="question">Question Text:</label>
                <textarea name="question" rows="8" cols="80" class='form-control'></textarea>
              </p>
              <p>
                <label for="option1">Option 1</label>
                <input type="text" name="option1" value="" class='form-control'>
              </p>
              <p>
                <label for="option2">Option 2</label>
                <input type="text" name="option2" value="" class='form-control'>
              </p>
              <p>
                <label for="option3">Option 3</label>
                <input type="text" name="option3" value="" class='form-control'>
              </p>
              <p>
                <label for="option4">Answer</label>
                <input type="text" name="option4" value="" class='form-control'>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $(document).ready(setTimeout(function() {
        $('#successmsg').css('display', 'none')
      }, 1000));

      $(document).ready(function() {
        $('li a').click(function() {
          $(this).addClass('active');
          $('li a').not(this).removeClass('active');
          if (($(this).attr('id')) == 'plink') {
            $('#profile').show();
            $('#userlist').hide();
            $('#questionadd').hide();
          }
          else if (($(this).attr('id')) == 'ulink') {
            $('#userlist').show();
            $('#profile').hide();
            $('#questionadd').hide();
          }
          else if (($(this).attr('id')) == 'qalink') {
            $('#questionadd').show();
            $('#profile').hide();
            $('#userlist').hide();
          }
        })
      })

    </script>
  </body>
</html>
