<?php
  require 'update.php';

  $stmt = $pdo->prepare("UPDATE sub_scr SET grp_scr = :scr");
  $stmt->execute(array(
    'scr' => htmlentities($scr)
  ));
?>
