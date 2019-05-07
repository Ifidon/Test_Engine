<?php
  require 'pdo.php';

  $request = $pdo->prepare("SELECT * FROM user where user_id = :id");
  $request->execute(array(
    ':id' => htmlentities($_GET['user'])
  ));
  $logged_in_user = $request->fetch(PDO::FETCH_ASSOC);


  $statement = $pdo->prepare("SELECT * FROM test where test_id = :id");
  $statement->execute(array(
    ':id' => htmlentities($_GET['test'])
  ));
  $this_test = $statement->fetch(PDO::FETCH_ASSOC);

  $qry = $pdo->prepare("SELECT * FROM org where org_id = :id");
  $qry->execute(array(
    ':id' => htmlentities($_GET['org'])
  ));
  $this_org = $qry->fetch(PDO::FETCH_ASSOC);
?>
