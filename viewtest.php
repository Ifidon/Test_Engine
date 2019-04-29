<?php
  session_start();
  require 'pdo.php';

  $stm = $pdo->prepare("SELECT * FROM test where test_id = :t_id");
  $stm->execute(array(
    ':t_id' => htmlentities($_GET['test'])
  ));
  $test = $stm->fetch(PDO::FETCH_ASSOC);

  $test_questions = [];
  $query = $pdo->prepare("SELECT * FROM question_grp where test_id = :t_id");
  $query->execute(array(
    ':t_id' => htmlentities($_GET['test'])
  ));
  $grps = $query->fetchAll(PDO::FETCH_ASSOC);
  // print_r($grps);

  foreach($grps as $grp) {
    $str = "SELECT * FROM question where category = :cat ORDER BY RAND() LIMIT ".$grp['grp_size'];
    $stmt = $pdo->prepare($str);
    $stmt->execute(array(
      ':cat' => htmlentities($grp['grp_category']),
      // ':len' => htmlentities($grp['grp_size'])
    ));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $res) {
      $res['grp'] = $grp['grp_id'];
      array_push($test_questions, $res);
      try {
        $quer = $pdo->prepare("INSERT INTO test_questions (grp_id, question_id, test_id) VALUES (:g_id, :q_id, :t_id)");
        $quer->execute(array(
          ':g_id' => htmlentities($grp['grp_id']),
          ':t_id' => htmlentities($_GET['test']),
          ':q_id' => htmlentities($res['question_id'])
        ));
      }
      catch (Exception $err) {
        continue;
      }
    }
  }

  // print_r($test_questions)
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>View Test</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./asset/scripts/orgview.js"></script>
  </head>
  <body>
    <?php
      require 'header.php';
    ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-8 order-3 m-auto p-4">
          <h5>Test generated: <?= $test['title'] ?></h5>
          <h5>No of questions generated: <?= sizeOf($test_questions) ?></h5>
          <?php
            foreach($test_questions as $t_q) {
              echo("<div class='border border-dark p-3 mt-3'");
              echo("<p class='small'><strong>".$t_q['category']."</strong></p>");
              echo("<p class='small'>".$t_q['text']."</p>");
              echo("<p class='small'> A: ".$t_q['option1']."</p>");
              echo("<p class='small'> B: ".$t_q['option2']."</p>");
              echo("<p class='small'> C: ".$t_q['option3']."</p>");
              echo("<p class='small'> D: ".$t_q['option4']." (Answer)</p>");
              echo("</div>");
              echo("<br>");
            }
          ?>
          <button type="button" name="button" onclick="window.close()" class="btn btn-md btn-dark float-right">Done</button>
        </div>
      </div>
    </div>
  </body>
</html>
