<?php
  session_start();
  require 'pdo.php';
  if (isset($_POST['user_type']) && isset($_POST['email']) && isset($_POST['password'])) {
    $type = htmlentities($_POST['user_type']);
    $email = htmlentities($_POST['email']);
    $pass = htmlentities($_POST['password']);
    $s_pass = hash('md5', $salt.$pass);
    if($_POST['user_type'] == 'unselected') {
      $_SESSION['error'] = "Please select an account type to login.";
      header("Location:login.php");
    }
    else if(strlen($type) < 1 || strlen($email) < 1 || strlen($pass) < 1) {
      $_SESSION['error'] = "Account type, Email and Password are required!";
      header("Location:login.php");
    }
    else if (!strpos($email, "@")){
      $_SESSION['error'] = "Login failed! Invalid account email (username)";
      header("Location:login.php");
    }
    else if ($type == 'org') {
      $sql = $pdo->prepare("SELECT short_org_name, org_password FROM org WHERE org_email = :email");
      $sql->execute(array(
        ':email' => $email
      ));
      $hit = $sql->fetch();
      print($hit);
      if (! $hit) {
        $_SESSION['error'] = "Login failed! User ($email) not found.";
        header("Location:login.php");
      }
      else if($s_pass == $hit['org_password']) {
        $_SESSION['success'] = "Login Successful!";
        $_SESSION['user'] = $hit['short_org_name'];
        $_SESSION['login'] = $email;
        // mail($to='e_fidon"yahoo.com', $subject='Login Notification', $message='Hello');
        header("Location:orgview.php?org=".urlencode($hit['short_org_name']));
      }
      else if ($s_pass !== $hit['org_password']) {
        $_SESSION['error'] = "Login failed! Password Incorrect.";
        header("Location:login.php");
      }
      else {
        $_SESSION['error'] = "Login failed! Please try again!";
        header("Location:login.php");
      }
    }
    else if($type = 'ind') {
      $sql = $pdo->prepare("SELECT * FROM user WHERE email = :email");
      $sql->execute(array(
        ':email' => $email
      ));
      $user = $sql->fetch();
      if (! $user) {
        $_SESSION['error'] = "Login failed! User ($email) not found.";
        header("Location:login.php");
      }
      else if($user && ($s_pass == $user['password'])) {
        $_SESSION['success'] = "Login Successful!";
        $_SESSION['user'] = $user['name'];
        $_SESSION['org_id'] = $user['org_id'];
        $_SESSION['login'] = $email;
        header("Location:persview.php?org=".urlencode($user['org_id'])."&user=".urlencode($user['user_id']));
      }
      else if ($user && ($s_pass !== $user['password'])) {
        $_SESSION['error'] = "Login failed! Password Incorrect.";
        header("Location:login.php");
      }
      else {
        $_SESSION['error'] = "Login failed! Please try again!";
        header("Location:login.php");
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>LOGIN PAGE</title>
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
            if(!isset($_POST['login'])) {
              unset($_SESSION['error']);
            }
          }
          else if(isset($_SESSION['success'])) {
              echo("<p class='alert alert-success'> Success: ".htmlentities($_SESSION['success'])."</p>");
              if(!isset($_POST['login'])) {
                unset($_SESSION['success']);
              }
            }
          ?>
        </div>
        <div class="col-xs-12 col-sm-8 order-3 m-auto">
          <h1 class="text-center text-info">Login</h1>
          <form class="form bg-light p-3 border border-dark" method="post">
            <p>
              <label for="type">Account Type:</label>
              <select class="form-control" name="user_type" id='type'>
                <option value="unselected">Select account type</option>
                <option value="org">Organization</option>
                <option value="ind">Individual</option>
              </select>
            </p>
            <p>
              <label for="email" class="">Account Email (Username):</label>
              <input type="text" name="email" value="" class="form-control" id='email'>
            </p>
            <p>
              <label for="pass" class="">Account Password:</label>
              <input type="password" name="password" value="" class="form-control" id="pass">
            </p>
            <p>
              <input type="submit" name="login" value="Login" class="form-control btn btn-info">
            </p>
          </form>
          <br>
          <p>
            Don't have an Account? Register as an
            <a href="org_registration.php">Organization</a>
            or
            <a href="ind_reg.php">Individual</a>
          </p>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#navlink').html("Register");
      $("#navlink").attr("href", "ind_reg.php");
    </script>

  </body>
</html>
