<?php
  require "pdo.php";

  $query = $pdo->prepare("SELECT user_id, name, email FROM user WHERE email = :email AND password= :pass");
  $query->execute(array(
    ':email' => $_REQUEST['userid'],
    ':pass' => hash('md5', $salt.$_REQUEST["password"])
  ));

  $result = $query->fetch(PDO::FETCH_ASSOC);
  // print_r($result);
  echo(json_encode($result));
?>
