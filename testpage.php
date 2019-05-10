<?php
  session_start();
  require 'unauthorized.php';
  require 'pdo.php';
  require 'getparams.php';

  $ins = $pdo->prepare("SELECT question_id, test_id, grp_id FROM test_questions WHERE test_id = :id");
  $ins->execute(array(
    ':id' => htmlentities($_GET['test'])
  ));
  $questions = $ins->fetchAll(PDO::FETCH_ASSOC);

  foreach($questions as $question) {
    try {
      $upd = $pdo->prepare("INSERT INTO user_test (user_id, question_id, test_id, grp_id) VALUES (:uid, :qid, :tid, :gid)");
      $upd->execute(array(
        ':uid' => htmlentities($_GET['user']),
        ':qid' => $question['question_id'],
        ':tid' => $question['test_id'],
        ':gid' => $question['grp_id']
      ));
    }
    catch(Exception $e) {
      continue;
    };
  };
  // SELECT * FROM test_questions JOIN question ON test_questions.question_id = question.question_id where test_id = :id
  $query = $pdo->prepare("SELECT COUNT(question_id), grp_category FROM user_test JOIN question_grp ON user_test.grp_id = question_grp.grp_id where user_test.user_id = :id GROUP BY user_test.grp_id");
  $query->execute(array(
    ':id' => htmlentities($_GET['user'])
  ));
  $groups = $query->fetchAll(PDO::FETCH_ASSOC);
  // $groups = json_encode($groups);
  // print_r($groups);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Test Introduction</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php require 'header.php'; ?>
    <div class="container">
      <div class="row border border-dark">
        <div class="col-8 order-3 m-auto p-3">
          <div class="my-4">
            <h4>Hello <?= $logged_in_user['name'] ?>,</h4>
            <p>
              Welcome to to the <b><?= $this_test['title'] ?></b> start page. This test has <b><?= sizeOf($groups) ?> section(s) </b> as shown in the table below.
              Please read the following INSTRUCTIONS CAREFULLY:
              <ol>
                <li> Click 'Start' to proceed with the corresponding section.</li>

              </ol>
            </p>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Section</th>
                  <th>No. of Questions</th>
                  <th>Duration</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($groups as $group) {
                    echo("<tr>");
                    echo("<td>".$group['grp_category']."</td>");
                    echo("<td>".$group['COUNT(question_id)']."</td>");
                    echo("<td>".$group['COUNT(question_id)']." minutes </td>");
                    echo("<td><a id ='strt' href='questionform.php?org=".urlencode($_GET['org'])."&user=".urlencode($_GET['user'])."&test=".urlencode($_GET['test'])."&section=".urlencode($group['grp_category'])."&question=0' target='_blank'>Start</a></td>");
                    echo("</tr>");
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    $('#navlink').html("Logout");
    $("#navlink").on('click', function() {
      confirm("Do you want proceed with log out?")
    });
    $("#navlink").attr("href", "logout.php");
    })
  </script>
</html>
