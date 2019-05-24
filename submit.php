<?php
  require 'update.php';
  // $_SESSION['completed'] = Array();
  // array_push($_SESSION['completed'], $cat);

  $qry = $pdo->prepare("SELECT grp_id FROM question_grp where grp_category = :cat");
  $qry->execute(array(
    ':cat' => $cat
  ));
  $gid = $qry->fetch(PDO::FETCH_ASSOC);


  $stmt = $pdo->prepare("UPDATE sub_scr SET grp_scr = :scr where user_id = :uid AND grp_id = :gid");
  $stmt->execute(array(
    ':scr' => htmlentities($scr),
    ':uid' => $id,
    ':gid' => htmlentities($gid['grp_id'])
  ));
?>
