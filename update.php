<?php
  session_start();
  require 'pdo.php';
  $data = $_POST;
  $all_questions = Array();
  array_push($all_questions, $data);

  $id = htmlentities($_SESSION['user_id']);
  $cat = htmlentities($_SESSION['cat']);

  $qury = $pdo->prepare("UPDATE user_test SET submission = :sub where user_id= :uid and question_id=:qid");
  $qury->execute(array(
    ':sub' => $data['submission'],
    ':uid' => $_SESSION['user_id'],
    ':qid' => $data['id']
  ));

  $qs = $pdo->prepare("SELECT user_test.user_id, user_test.question_id, option4, submission, user_test.grp_id FROM user_test JOIN question_grp ON user_test.grp_id = question_grp.grp_id JOIN question ON user_test.question_id = question.question_id where user_test.user_id = :uid AND user_test.question_id = :qid");
  $qs1 = $qs->execute(array(
    ':uid' => $id,
    ':qid' => $data['id']
  ));
  $qs2 = $qs->fetch(PDO::FETCH_ASSOC);

  $score;
  if($qs2['submission'] == $qs2['option4']) {
    $score = 1;
  }
  else {
    $score = 0;
  }
  $query = $pdo->prepare("UPDATE user_test SET score = :scr where user_id= :uid and question_id=:qid");
  $query->execute(array(
    ':scr' => $score,
    ':uid' => $qs2['user_id'],
    ':qid' => $qs2['question_id']
  ));

  $stm = $pdo->prepare("SELECT SUM(score) FROM user_test JOIN question_grp ON user_test.grp_id = question_grp.grp_id where grp_category = :cat AND user_id = :uid");
  $stm->execute(array(
    ':cat' => $cat,
    ':uid' => $id
  ));
  $total_score = $stm->fetch(PDO::FETCH_ASSOC);
  print_r($total_score);
  $scr = $total_score['SUM(score)'];
?>
