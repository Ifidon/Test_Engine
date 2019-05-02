<?php
session_start();
require 'unauthorized.php';
require 'pdo.php';
  if(isset($_GET['user']) && $_GET['delete']) {
    $query = $pdo->prepare("DELETE FROM user where user_id = :u_id");
    $query->execute(array(
      ':u_id' => htmlentities($_GET['user'])
    ));
    unset($_GET['user']);
    unset($_GET['delete']);
    header("Location:orgview.php?org=".urlencode($_SESSION['user']));
  }
  else if(isset($_GET['question']) && $_GET['delete']) {
    $query = $pdo->prepare("DELETE FROM question where question_id = :q_id");
    $query->execute(array(
      ':q_id' => htmlentities($_GET['question'])
    ));
    unset($_GET['question']);
    unset($_GET['delete']);
    header("Location:orgview.php?org=".urlencode($_SESSION['user']));
  }
  else if(isset($_GET['test']) && $_GET['delete']) {
    $query = $pdo->prepare("DELETE FROM test where test_id = :t_id");
    $query->execute(array(
      ':t_id' => htmlentities($_GET['test'])
    ));
    unset($_GET['test']);
    unset($_GET['delete']);
    header("Location:orgview.php?org=".urlencode($_SESSION['user']));
  }
?>
