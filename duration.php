<?php
  require 'pdo.php';
  $test = $pdo->query("SELECT * FROM test");
  $res = $test->fetchAll(PDO::FETCH_ASSOC);
  $res = json_encode($res[0]);
  echo($res);
?>
