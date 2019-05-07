<?php
  session_start();
  require 'unauthorized.php';
  require 'pdo.php';
  require 'getparams.php';
  // print_r(urldecode($_GET['section']));

  $query = $pdo->prepare("SELECT text, option1, option2, option3, option4, grp_category FROM test_questions JOIN question ON test_questions.question_id = question.question_id JOIN question_grp ON test_questions.grp_id = question_grp.grp_id where test_questions.test_id = :t_id AND grp_category= :cat");
  $query->execute(array(
    ':t_id' => urldecode($_GET['test']),
    ':cat' => urldecode($_GET['section'])
  ));
  $set = $query->fetchAll(PDO::FETCH_ASSOC);
  shuffle($set);
  // print_r($set);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?= $_GET['section'] ?> Section - <?= $this_test['title'] ?></title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./asset/scripts/countdown.js"></script>
    <link rel="stylesheet" href="./asset/styles/countdown.css">
  </head>
  <body>
    <?php require 'header.php' ?>
    <div class="container">
      <div class="row">
        <div class="col-8 order-3 m-auto">
          <?php
            require 'timer.php'
          ?>
          <h6>Examiner/Organization: <?= $this_org['long_org_name'] ?></h6>
          <h6>Candidate: <?= $logged_in_user['name'] ?> </h6>
          <p class="small">
            Test: <?= $this_test['title']?><br>
            Section: <?= urldecode($_GET['section']) ?><br>
            Duration: <span id="dur"><?= sizeOf($set) ?></span> minutes (Please see TIMER)<br>
          <p>
          <div class="form-group form-responsive border border-dark">
            <?php
              $question = $set[$_GET['question']];
              $question_text = $question['text'];
              $question_opts = array($question['option1'], $question['option2'], $question['option3'], $question['option4']);
              shuffle($question_opts);
            ?>
            <form class="form m-3">
              <ol>
                <li start=<?= $_GET['question'] +1 ?>>
                  <h6>
                    <?= $question_text ?>
                  </h6>
                </li>
              </ol>
              <p class=pl-5>
                <input  class="my-2" type="radio" name="submission" value=<?= $question_opts[0] ?> > <?= $question_opts[0] ?><br>
                <input class="my-2" type="radio" name="submission" value=<?= $question_opts[1] ?> > <?= $question_opts[1] ?><br>
                <input class="my-2" type="radio" name="submission" value=<?= $question_opts[2] ?> > <?= $question_opts[2] ?><br>
                <input class="my-2" type="radio" name="submission" value=<?= $question_opts[3] ?> > <?= $question_opts[3] ?><br>
              </p>
              <div class="mx-4 my-3">
                <button class="btn btn-primary" type="button" name="prev" id="prev">Previous</button>
                <button class="btn btn-success float-right" type="button" name="next" id="next">Next</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
