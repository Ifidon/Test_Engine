<?php
  session_start();
  require 'pdo.php';

  $test_questions = [];
  $query = $pdo->prepare("SELECT * FROM question_grp where test_id = :t_id");
  $query->execute(array(
    ':t_id' => htmlentities($_GET['test_id'])
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

      $quer = $pdo->prepare("INSERT INTO test_questions (grp_id, question_id, test_id) VALUES (:g_id, :q_id, :t_id)");
      $quer->execute(array(
        ':g_id' => htmlentities($grp['grp_id']),
        ':t_id' => htmlentities($_GET['test_id']),
        ':q_id' => htmlentities($res['question_id'])
      ));
      // print_r($res);
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
        <div class="col-8 order-3 m-auto">
          <?php
            foreach($test_questions as $t_q) {
              echo("<div class='border border-dark p-3'");
              echo("<p> ".$t_q['text']."</p>");
              echo("<p> A: ".$t_q['option1']."</p>");
              echo("<p> B: ".$t_q['option2']."</p>");
              echo("<p> C: ".$t_q['option3']."</p>");
              echo("<p> D: ".$t_q['option4']." (Answer)</p>");
              echo("</div>");
              echo("<br>");
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
