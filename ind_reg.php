<?php
  session_start();
  require 'pdo.php';

  $users = $pdo->query('SELECT * FROM user');
  $users = $users->fetchAll();

  $orgs = $pdo->query('SELECT * FROM org');
  $orgs = $orgs->fetchAll();

  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $orgn = htmlentities($_POST['org']);
    $name = htmlentities($_POST['name']);
    $email = htmlentities($_POST['email']);
    $pass = htmlentities($_POST['password']);
    $s_pass = hash('md5', $salt.$pass);
    if(strlen($email) < 1 || strlen($pass) < 1 || $orgn == 'unselected') {
      $_SESSION['error'] = "Organization, email and password are required!";
      header("Location: ind_reg.php");
    }
    else if (!strpos($email, "@") || !strpos($email, ".")){
      $_SESSION['error'] = "Email must be a valid email address";
      header("Location: ind_reg.php");
    }
    else if (count($users) > 0) {
      foreach ($users as $user) {
        if ($user['email'] == $email) {
          $_SESSION['error'] = "User ($email) already exists. Please login to continue!";
          header("Location:login.php");
          break;
        }
      }
      $sql = $pdo->prepare("INSERT INTO user (org_id, name, email, password) VALUES (:org, :name, :email, :pass)");
      $sql->execute(array(
        ':org' => $orgn,
        ':name' => strtoupper($name),
        ':email' => strtolower($email),
        ':pass' => $s_pass
      ));
      $_SESSION['success'] = "User Registration Successful";
      $_SESSION['user'] = $email;
      $id = $pdo->lastInsertId();
      header("Location:orgs.php?org=".urlencode($orgn)."&user=".urlencode($id));
    }
    else {
      $sql = $pdo->prepare("INSERT INTO user (org_id, name, email, password) VALUES (:org, :name, :email, :pass)");
      $sql->execute(array(
        ':org' => $orgn,
        ':name' => strtoupper($name),
        ':email' => strtolower($email),
        ':pass' => $s_pass
      ));
      $_SESSION['success'] = "User Registration Successful";
      $_SESSION['user'] = $email;
      $id = $pdo->lastInsertId();
      header("Location:orgs.php?org=".urlencode($orgn)."&user=".urlencode($id));
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>NEW USER</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php
      require 'header.php';
    ?>
    <div class="container">
      <div class="row p-3">
        <div class="col-8 order-3 m-auto">
          <?php
          if(isset($_SESSION['error'])) {
            echo("<p class='alert alert-danger'> Error: ".htmlentities($_SESSION['error'])."</p>");
            if(!isset($_POST['create'])) {
              unset($_SESSION['error']);
            }
          }
          else if(isset($_SESSION['success'])) {
              echo("<p class='alert alert-success'> Success: ".htmlentities($_SESSION['success'])."</p>");
              if(!isset($_POST['create'])) {
                unset($_SESSION['success']);
              }
            }
          ?>
        </div>
        <div class="col-8 order-3 m-auto">
          <h1 class="text-center text-info">Create New User</h1>
          <form class="form bg-light p-3 border border-dark" method="post">
            <p class="text-center"><em>Please fill out the form below to register.<em> </p>
            <p>
              <label for="org" class="">Organization:</label>
              <select class="form-control" name="org" id='org'>
                <option value="unselected">Select your organization</option>
                <?php
                  foreach($orgs as $org) {
                    echo("<option value=".$org['org_id'].">".$org['short_org_name']."</option>");
                  }
                ?>
              </select>
            </p>
            <p>
              <label for="name" class="">Full Name (optional):</label>
              <input type="text" name="name" value="" class="form-control" id="name">
            </p>
            <p>
              <label for="email" class="">Email (Username):</label>
              <input type="text" name="email" value="" class="form-control" id='email'>
            </p>
            <p>
              <label for="pass" class="">Password:</label>
              <input type="password" name="password" value="" class="form-control" id="pass">
            </p>
            <p>
              <input type="submit" name="create" value="Create User" class="form-control btn btn-info">
            </p>
          </form>
          <p>
            Already have an accout?
            <a href="login.php">Login</a>
          </p>
        </div>
      </div>
    </div>
  </body>
</html>
