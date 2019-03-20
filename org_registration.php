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
    session_start();
    require 'header.php';
    require 'pdo.php';
    $salt = "7Xyt@IohtGdr002P";
    $orgs = $pdo->query("SELECT * FROM org");
    $orgs = $orgs->fetchAll();
    // print_r($_SESSION);
    // print_r($orgs);
    //
    // print_r(count($orgs));

    if (isset($_POST['long_text']) && isset($_POST['short_text']) && isset($_POST['email']) && isset($_POST['password'])) {
      $lname = $_POST['long_text'];
      $sname = $_POST['short_text'];
      $email = $_POST['email'];
      $pass = $_POST['password'];
      print_r($_POST);
      if (strlen($lname) < 1 || strlen($sname) < 1 || strlen($email) < 1 || strlen($pass) < 1) {
        $_SESSION['message'] = "All fields are required";
        header("Location: org_registration.php");
      }
      else if (!strpos($email, "@")) {
        $_SESSION['message'] = "Please provide a valid email/username for the organization";
        header("Location: org_registration.php");
      }
      else if (count($orgs) > 0) {
        foreach ($orgs as $org) {
          if ($org['org_email'] == $email || $org['long_org_name'] == $lname || $org['short_org_name'] == $sname) {
            $_SESSION['message'] = "Organization ($lname) already exists. Please login to continue!";
            header("Location:login.php");
          }
        }
      }
      else {
        $sql = $pdo->prepare('INSERT INTO org (long_org_name, short_org_name, org_email, org_password) VALUES (:lname, :sname, :email, :pass)');
        $sql->execute(array(
          ':lname' => $lname,
          ':sname' => $sname,
          ':email' => $email,
          ':pass' => hash('md5', $salt.$pass)
        ));
        $_SESSION['message'] = 'Record Created';
      }
    }
    // session_destroy()
   ?>
   <div class="container">
     <div class="row">
       <div class="col-8 order-3 m-auto">
         <p class="text-danger">
           <?= $_SESSION['message'] ?>
         </p>
         <form class="form bg-secondary text-white" method="post">
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
             <input type="text" name="password" value="" class="form-control" id="pass">
           </p>
           <p>
             <input type="submit" name="create" value="Register Organization" class="form-control">
           </p>
         </form>
       </div>
     </div>
   </div>
  </body>
</html>
