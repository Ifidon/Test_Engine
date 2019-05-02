<?php
session_start();
require 'pdo.php';
// $salt = "7Xyt@IohtGdr002P";
$orgs = $pdo->query("SELECT * FROM org");
$orgs = $orgs->fetchAll();
if (isset($_POST['long_text']) && isset($_POST['short_text']) && isset($_POST['email']) && isset($_POST['password'])) {
  $lname = htmlentities($_POST['long_text']);
  $sname = htmlentities($_POST['short_text']);
  $email = htmlentities($_POST['email']);
  $pass = htmlentities($_POST['password']);

  if (strlen($lname) < 1 || strlen($sname) < 1 || strlen($email) < 1 || strlen($pass) < 1) {
    $_SESSION['error'] = "All fields are required";
    header("Location: org_registration.php");
  }
  else if (!strpos($email, "@") || !strpos($email, ".")) {
    $_SESSION['error'] = "Please provide a valid email/username for the organization";
    header("Location: org_registration.php");
  }
  else if (count($orgs) > 0) {
    foreach ($orgs as $org) {
      if ($org['org_email'] == $email || $org['long_org_name'] == $lname || $org['short_org_name'] == $sname) {
        $_SESSION['error'] = "Organization ($lname) already exists. Please login to continue!";
        header("Location:login.php");
        break;
      }
    }
    $sql = $pdo->prepare('INSERT INTO org (long_org_name, short_org_name, org_email, org_password) VALUES (:lname, :sname, :email, :pass)');
    $sql->execute(array(
      ':lname' => strtoupper($lname),
      ':sname' => strtoupper($sname),
      ':email' => strtolower($email),
      ':pass' => hash('md5', $salt.$pass)
    ));
    $_SESSION['success'] = 'Record Created!';
    $_SESSION['org'] = $sname;
    $_SESSION['login'] = $email;
    header("Location:orgview.php?org=".urlencode($_SESSION['org_name']));
  }
  else {
    $sql = $pdo->prepare('INSERT INTO org (long_org_name, short_org_name, org_email, org_password) VALUES (:lname, :sname, :email, :pass)');
    $sql->execute(array(
      ':lname' => strtoupper($lname),
      ':sname' => strtoupper($sname),
      ':email' => strtolower($email),
      ':pass' => hash('md5', $salt.$pass)
    ));
    $_SESSION['success'] = 'Record Created!';
    $_SESSION['org'] = $sname;
    $_SESSION['login'] = $email;
    header("Location:orgview.php?org=".urlencode($_SESSION['org_name']));
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Organization Registration</title>
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
         <h1 class="text-center text-info">Create New Account (Organization)</h1>
         <form class="form bg-light p-3 border border-dark" method="post">
           <p class="text-center"><em>Please fill out the form below to register your organization. <br><br><span class="text-danger"> All fields are mandatory</span><em> </p>
           <p>
             <label for="long" class="">Organization Name (Long text):</label>
             <input type="text" name="long_text" value="" class="form-control" id="long">
           </p>
           <p>
             <label for="short" class="">Organization Name (Short text):</label>
             <input type="text" name="short_text" value="" class="form-control" id="short">
           </p>
           <p>
             <label for="email" class="">Organization Email (Username):</label>
             <input type="text" name="email" value="" class="form-control" id='email'>
           </p>
           <p>
             <label for="pass" class="">Organization Password:</label>
             <input type="password" name="password" value="" class="form-control" id="pass">
           </p>
           <p>
             <input type="submit" name="create" value="Register Organization" class="form-control btn btn-info">
           </p>
         </form>
         <p>
           Already Registered?
           <a href="login.php">Login</a>
         </p>
       </div>
     </div>
   </div>
   <script type="text/javascript">
     $('#navlink').html("Login");
     $("#navlink").attr("href", "login.php");
   </script>
  </body>
</html>
