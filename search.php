<?php
  session_start();
  require "unauthorized.php";
  include dirname(__FILE__).'\pdo.php';
  $stm = $pdo->query("SELECT question_id, text, option1, option2, option3,option4 FROM question");
  $res = $stm->fetchAll(PDO::FETCH_ASSOC);
  echo(json_encode($res));
?>
