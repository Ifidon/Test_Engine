<?php
session_start();
require 'pdo.php';
$type;
$data;
  if (isset($_GET['test']) && $_GET['edit']) {
    $type = "Test";
    $test_id = $_GET['test'];
    $query = $pdo->prepare("SELECT title, duration, length FROM test where test_id = :t_id");
    $query->execute(array(
      ':t_id' => htmlentities($test_id)
    ));
    $test = $query->fetch(PDO::FETCH_ASSOC);
    $data = $test;
    // $test = json_encode($test);
    // print_r($test);
  }
  else if (isset($_GET['question']) && $_GET['edit']) {
    $type = "Question";
    $ques_id = $_GET['question'];
    $query = $pdo->prepare("SELECT text, option1, option2, option3, option4, multiple, category FROM question where question_id = :q_id");
    $query->execute(array(
      ':q_id' => htmlentities($ques_id)
    ));
    $question = $query->fetch(PDO::FETCH_ASSOC);
    $data = $question;
  }

  if(isset($_POST['update'])) {
    if($type === 'Test') {
      $stmt = $pdo->prepare("UPDATE test SET title = :ttl, duration = :dur, length = :len WHERE test_id = :t_id");
      $stmt->execute(array(
        ':t_id' => htmlentities($_POST['test']),
        ':ttl' => htmlentities($_POST['title']),
        ':dur' => htmlentities($_POST['duration']),
        ':len' => htmlentities($_POST['length'])
      ));
      unset($_GET['test']);
      unset($_GET['edit']);
      header("Location:orgview.php?org=".urlencode($_SESSION['org']));
    }
    else if ($type === 'Question') {
      {
        $stmt = $pdo->prepare("UPDATE question SET text = :txt, option1 = :opt1, option2 = :opt2, option3 = :opt3, option4 = :opt4, multiple = :mul, category = :cat WHERE question_id = :q_id");
        $stmt->execute(array(
          ':q_id' => htmlentities($_GET['question']),
          ':txt' => htmlentities($_POST['text']),
          ':opt1' => htmlentities($_POST['option1']),
          ':opt2' => htmlentities($_POST['option2']),
          ':opt3' => htmlentities($_POST['option3']),
          ':opt4' => htmlentities($_POST['option4']),
          ':mul' => htmlentities($_POST['multiple']),
          ':cat' => htmlentities($_POST['category'])
        ));
        unset($_GET['question']);
        unset($_GET['edit']);
        header("Location:orgview.php?org=".urlencode($_SESSION['org']));
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit Page</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./asset/scripts/edit.js"></script>
  </head>
  <body>
    <?php require 'header.php' ?>
    <div class="container-fluid">
      <div vlass="row">
        <div class="col-8 order-3 m-auto">
          <div class="form-group border border-dark p-3 m-3">
            <h4 class='text-center'>Edit <?= $type ?> Details</h4>
            <form class=" form" method="post">
              <?php
              foreach($data as $key => $value) {
                $label = ucfirst($key);
                if ($label == 'Option4' && $type == "Question") {
                  $label = "Answer";
                }
                else {
                  $label = $label;
                }
                // print_r($label);
                if(strlen($value) > 50) {
                  echo("<p>");
                  echo("<label for='".$key."'>$type $label:</label>");
                  echo("<textarea class='form-control' name='".$key."'>".htmlentities($value)."</textarea>");
                  echo("</p>");
                }
                else {
                  echo("<p>");
                  echo("<label for='".$key."'>$type $label:</label>");
                  echo("<input class='form-control' name='".$key."' value='".htmlentities($value)."'>");
                  echo("</p>");
                }
              }
              ?>
              <input type="submit" name="update" id='updbtn' value="Update <?=$type?>" class="btn btn-secondary form-control">
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
