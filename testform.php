<?php
  session_start();
  require 'pdo.php';

  $qury = $pdo->prepare("SELECT user_test.user_id, user_test.question_id, text, option1, option2, option3, option4, user_test.grp_id FROM user_test JOIN question ON user_test.question_id = question.question_id JOIN question_grp ON user_test.grp_id = question_grp.grp_id where user_test.user_id = :id AND grp_category = :cat");
  $qury->execute(array(
    ':id' => htmlentities($_SESSION['user_id']),
    ':cat' => htmlentities($_SESSION['cat'])
  ));
  $res = $qury->fetchAll(PDO::FETCH_ASSOC);

  $gid = htmlentities($res[0]['grp_id']);
  $stm = $pdo->prepare("UPDATE user_test SET submission = :sub, score = :scr WHERE user_id = :uid AND grp_id = :gid");
  $stm->execute(array(
    ':sub' => "",
    ':scr' => 0,
    ':uid' => htmlentities($_SESSION['user_id']),
    ':gid' => $gid
  ));
  // print_r($_SESSION['user_id']);
  // shuffle($res);

  $out = array();
  foreach($res as $re) {
    unset($re['submission']);
    $opts = array($re['option1'], $re['option2'], $re['option3'], $re['option4']);
    shuffle($opts);
    $new = array("id" => $re['question_id'], 'question' => $re['text'], 'a' => $opts[0], 'b' => $opts[1], 'c' => $opts[2], 'd' => $opts[3]);
    array_push($out, $new);
  };
  shuffle($out);
  echo(json_encode($out));
?>
