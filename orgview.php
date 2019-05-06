<?php
  session_start();
  require "unauthorized.php";
  // var_dump($_SESSION);
  require 'pdo.php';

  $sql = $pdo->prepare('SELECT * FROM org where org_email = :email');
  $sql->execute(array(
    ':email' => $_SESSION['login']
  ));
  $org = $sql->fetch(PDO::FETCH_ASSOC);
  // var_dump($org);

  $sql2 = $pdo->prepare('SELECT * FROM user WHERE org_id = :id');
  $sql2->execute(array(
    ':id' => $org['org_id']
  ));
  $users = $sql2->fetchAll();

  $sql3 = $pdo->prepare('SELECT * FROM question ORDER BY question_id DESC LIMIT 5');
  $sql3->execute();
  $questions = $sql3->fetchAll();
  // $questions = array_reverse($questions);

  $sql4 = $pdo->prepare('SELECT * FROM test where org_id = :id');
  $sql4->execute(array(
    ':id' => $org['org_id']
  ));
  $tests = $sql4->fetchAll();

  if (isset($_POST['question']) && isset($_POST['option1']) && isset($_POST['option4'])) {
    $quest = htmlentities($_POST['question']);
    $opt1 = htmlentities($_POST['option1']);
    $opt2 = htmlentities($_POST['option2']);
    $opt3 = htmlentities($_POST['option3']);
    $opt4 = htmlentities($_POST['option4']);
    $mult = htmlentities($_POST['multiple']);
    $cat = htmlentities($_POST['category']);
    $sql4 = $pdo->prepare('INSERT INTO question (text, option1, option2, option3, option4, multiple, category) VALUES (:quest, :opt1, :opt2, :opt3, :opt4, :mul, :cat)');
    $sql4->execute(array(
      ':quest' => $quest,
      ':opt1' => $opt1,
      ':opt2' => $opt2,
      ':opt3' => $opt3,
      ':opt4' => $opt4,
      ':mul' => $mult,
      ':cat' => $cat
    ));
    $id = $pdo->lastInsertId();
    $added = $pdo->prepare('SELECT * FROM question WHERE question_id = :id');
    $added->execute(array(
      ':id' => $id
    ));
    $added = $added->fetch(PDO::FETCH_ASSOC);
    header("Location: orgview.php?org=".urlencode($org['short_org_name']));
  }
  // var_dump($users);

  // $sql3 = $pdo->prepare('SELECT * FROM test WHERE org_id = :id');
  // $sql3->execute(array(
  //   ':id' => $org['org_id']
  // ));
  // $tests = $sql3->fetchAll();
  // var_dump($tests);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ORGANIZATION VIEW</title>
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
      <div vlass="row">
        <div class="col-8 order-3 m-auto">
            <?php
            if(isset($_SESSION['success'])) {
                echo("<p class='alert alert-success' id='successmsg'> Success: ".htmlentities($_SESSION['success'])."</p>");
                unset($_SESSION['success']);
              }
            ?>
            <h4 class="text-center"> Welcome, <?= htmlentities($org['short_org_name']) ?></h4>
            <p class="float-right text-info small"><strong> <?= "Date: ". date("d M Y") ?></strong></p>
          <ul class='nav nav-tabs my-5'>
            <li class='nav-item mx-auto'>
              <a href="#" class='nav-link' id='plink'>Profile View</a>
            </li>
            <li class='nav-item mx-auto'>
              <a href="#" class='nav-link' id='ulink'>Users</a>
            </li>
            <li class='nav-item mx-auto'>
              <a href="#" class='nav-link' id='qlink'>Questions</a>
            </li>
            <li class='nav-item mx-auto'>
              <a href="#" class='nav-link' id='tlink'>Tests</a>
            </li>
          </ul>
          <div class="form-group">
            <form class="form border border-dark p-3" method="post" id='profile'>
              <h4 class="text-center"><?= $org['long_org_name'] ?> - PROFILE VIEW</h4>
              <p>
                <label for="long">Organization Name:</label>
                <input type="text" name="long_org_name" value="<?= htmlentities($org['long_org_name']) ?>" class='form-control' onclick="disabled=true">
              </p>
              <p>
                <label for="short">Organization Short Code:</label>
                <input type="text" name="short_org_name" value="<?= htmlentities($org['short_org_name']) ?>" class='form-control'>
              </p>
              <p>
                <label for="email">Organization Email/Username:</label>
                <input type="text" name="org_email" value="<?= htmlentities($org['org_email']) ?>" class='form-control' onclick="disabled=true">
              </p>
            </form>
          </div>
          <div class="table-responsive border border-dark p-3" id='userlist' style="display:none">
            <h4 class='text-center mb-3'>LIST OF REGISTERED USERS FOR <strong><?= $org['short_org_name'] ?></strong></h4>
            <table class='table table-bordered table-striped'>
              <thead>
                <tr>
                  <th>User ID</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Phone No</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($users as $user) {
                    echo("<tr>");
                    echo("<th scope='row'>".$user['user_id']."</th>");
                    echo("<td>".$user['name']."</td>");
                    echo("<td>".$user['email']."</td>");
                    echo("<td>".$user['tel']."</td>");
                    echo("<td class='small'><a href='edit.php?user=".urlencode($user['user_id'])."&edit=True' class='d-block' id=''>Edit</a><a href='delete.php?user=".urlencode($user['user_id'])."&delete=True' class='d-block' id=''>Delete</a></td>");
                    echo("</tr>");
                  }
                ?>
              </tbody>
            </table>
            <button type="button" name="button" class="btn btn-secondary ml-auto">Add New User</button>
          </div>
          <div class=" table-responsive border border-dark p-3 m-3" id="questionlist" style="display:none">
            <h4 class='text-center'>List of Questions for <?= $org['short_org_name'] ?></h4>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class='small'>Q_ID</th>
                  <th class='small'>QUESTION</th>
                  <th class='small'>OPTION 1</th>
                  <th class='small'>OPTION 2</th>
                  <th class='small'>OPTION 3</th>
                  <th class='small'>ANSWER</th>
                  <th class='small'>MULTIPLE ANSWER</th>
                  <th class='small'>CATEGORY</th>
                  <th class='small'>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($questions as $question) {
                    echo("<tr>");
                    echo("<th scope='row' class='small'>".$question['question_id']."</th>");
                    echo("<td class='small'>".$question['text']."</td>");
                    echo("<td class='small'>".$question['option1']."</td>");
                    echo("<td class='small'>".$question['option2']."</td>");
                    echo("<td class='small'>".$question['option3']."</td>");
                    echo("<td class='small'>".$question['option4']."</td>");
                    echo("<td class='small'>".$question['multiple']."</td>");
                    echo("<td class='small'>".$question['category']."</td>");
                    echo("<td class='small'><a href='edit.php?question=".urlencode($question['question_id'])."&edit=True' class='d-block'>Edit</a><a href='delete.php?question=".urlencode($question['question_id'])."&delete=True' class='d-block'>Delete</a></td>");
                    echo("</tr>");
                  }
                ?>
              </tbody>
            </table>
            <button type="button" name="button" class="btn btn-secondary ml-auto" data-toggle="modal" data-target="#newquestion">Add New Question</button>
          </div>
          <div class="table-responsive border border-dark p-3 m-3" style="display:none" id="testlist">
            <h4 class='text-center my-4'>List of Tests for <?= $org['short_org_name'] ?> </h4>
            <table class="table table-center table-bordered table-striped" id="testtable">
              <thead>
                <tr>
                  <th>Test ID</th>
                  <th>Test Title</th>
                  <th>No.of Questions</th>
                  <th>Duration (Mins.)</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach($tests as $test) {
                  echo("<tr>");
                  echo("<th scope='row' class='small'>".$test['test_id']."</th>");
                  echo("<td class='small'>".$test['title']."</td>");
                  echo("<td class='small'>".$test['length']."</td>");
                  echo("<td class='small'>".$test['duration']."</td>");
                  echo("<td class='small'><a href='edit.php?test=".urlencode($test['test_id'])."&edit=True'>Edit</a> / <a href='delete.php?test=".urlencode($test['test_id'])."&delete=True'>Delete</a> / <a href='viewtest.php?test=".urlencode($test['test_id'])."' target='_blank'>Generate Test</a></td>");
                  echo("</tr>");
                }
                ?>
              </tbody>
            </table>
            <button type="button" name="button" class="btn btn-secondary ml-auto">Add New Test</button>
          </div>

          <div id="newquestion" class="modal" tabindex = "-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="text-center modal-title">Add new question</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body p-4">
                  <form class="form" method="post">
                    <p>
                      <label for="question">Question Text:</label>
                      <textarea name="question" rows="8" cols="80" class='form-control' value="<?= htmlentities($added['text'])?>"></textarea>
                    </p>
                    <p>
                      <label for="option1">Option 1</label>
                      <input type="text" name="option1" value="" class='form-control'>
                    </p>
                    <p>
                      <label for="option2">Option 2</label>
                      <input type="text" name="option2" value="" class='form-control'>
                    </p>
                    <p>
                      <label for="option3">Option 3</label>
                      <input type="text" name="option3" value="" class='form-control'>
                    </p>
                    <p>
                      <label for="option4">Answer</label>
                      <input type="text" name="option4" value="" class='form-control'>
                    </p>
                    <p>
                      <label for="multiple">Multiple Answers?</label>
                      <select class="form-control" name="multiple" id="multiple">
                        <option value="True">Yes</option>
                        <option value="False" selected>No</option>
                      </select>
                    </p>
                    <p>
                      <label for="cat">Category</label>
                      <input type="text" name="category" value="" class='form-control' id="cat">
                    </p>
                    <input type="submit" name="save_question" value="Add Question" class="btn btn-secondary">
                  </form>
                </div>
                <div class="modal-footer">

                </div>
              </div>
            </div>
          </div>
          <div class="form-group modal-fade" id="newquestion" hidden>

              <form class="form" method="post">
                <p>
                  <label for="question">Question Text:</label>
                  <textarea name="question" rows="8" cols="80" class='form-control' value="<?= htmlentities($added['text'])?>"></textarea>
                </p>
                <p>
                  <label for="option1">Option 1</label>
                  <input type="text" name="option1" value="" class='form-control'>
                </p>
                <p>
                  <label for="option2">Option 2</label>
                  <input type="text" name="option2" value="" class='form-control'>
                </p>
                <p>
                  <label for="option3">Option 3</label>
                  <input type="text" name="option3" value="" class='form-control'>
                </p>
                <p>
                  <label for="option4">Answer</label>
                  <input type="text" name="option4" value="" class='form-control'>
                </p>
                <p>
                  <label for="multiple">Multiple Answers?</label>
                  <select class="form-control" name="multiple" id="multiple">
                    <option value="True">Yes</option>
                    <option value="False" selected>No</option>
                  </select>
                </p>
                <p>
                  <label for="cat">Category</label>
                  <input type="text" name="category" value="" class='form-control' id="cat">
                </p>
                <input type="submit" name="save" value="Add Question" class="btn btn-secondary">
              </form>
            </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#navlink').html("Logout");
      $("#navlink").on('click', function() {
        confirm("Do you want proceed with log out?")
      });
      $("#navlink").attr("href", "logout.php");
    </script>
  </body>
</html>
