<?php
  session_start();
  require 'unauthorized.php';
  require 'pdo.php';
  require 'getparams.php';
  // print_r(urldecode($_GET['section']));
  $_SESSION['cat'] = $_GET['section'];
  $_SESSION['user_id'] = $_GET['user'];

  $query = $pdo->prepare("SELECT text, option1, option2, option3, option4, grp_category FROM test_questions JOIN question ON test_questions.question_id = question.question_id JOIN question_grp ON test_questions.grp_id = question_grp.grp_id where test_questions.test_id = :t_id AND grp_category= :cat");
  $query->execute(array(
    ':t_id' => urldecode($_GET['test']),
    ':cat' => urldecode($_GET['section'])
  ));
  $set = $query->fetchAll(PDO::FETCH_ASSOC);
  shuffle($set);
  // print_r($set);
  // $set2 = json_encode($set);
  // echo($set2);

  try {
    $ins = $pdo->prepare("INSERT INTO sub_scr (user_id, grp_id) VALUES (:uid, :gid)");
    $ins->execute(array(
      ':uid' => htmlentities($_GET['user']),
      ':gid' => htmlentities($_GET['gid'])
    ));
  }
  catch(Exception $e) {

  }
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
          <div class="form-group form-responsive border border-dark float-left p-1">
            <form class="form m-3" id='qform'>
              <ol>
                <li id='sn'>
                  <h6 id='text'></h6>
                </li>
              </ol>
              <p class=pl-5>
                <input id='opt1' class="my-2" type="radio" name="submission" value='' required><span id='a' class='m-2'></span><br>
                <input id='opt2' class="my-2" type="radio" name="submission" value=''><span id='b' class='m-2'></span><br>
                <input id='opt3' class="my-2" type="radio" name="submission" value=''><span id='c' class='m-2'></span><br>
                <input id='opt4' class="my-2" type="radio" name="submission" value=''><span id='d' class='m-2'></span><br>
              </p>
              <div class="mx-4 my-3 p-2">
                <button class="btn btn-primary" type="button" name="prev" id="prev">Previous</button>
                <button class="btn btn-success float-right" type="button" name="next" id="next">Next</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      function setinput(questions=[], i=0) {
            $("#text").html(questions[i]['question'])
            $('#opt1').val(questions[i]['a']);
            $("#a").html(questions[i]['a']);
            $('#opt2').val(questions[i]['b']);
            $("#b").html(questions[i]['b']);
            $('#opt3').val(questions[i]['c']);
            $("#c").html(questions[i]['c']);
            $('#opt4').val(questions[i]['d']);
            $("#d").html(questions[i]['d']);
            $("#sn").val(i+1);
            if(i == 0) {
              $("#prev").hide();
            }
            else {
              $("#prev").show();
            }
            if( i == questions.length-1) {
              $("#next").html("Submit");
            }
            else {
              $("#next").html("Next");
            }
      };


      $(document).ready(function() {
        var i = 0;
        $.getJSON('testform.php', function(data) {
          // console.log(data);
          setinput(data, i);

          $("#next").click(function() {
            if($("#next").html() == "Submit") {
              data[i]['submission'] = $(":checked").val();
              $.post('submit.php', data[i], function(result) {
                console.log(result)
              });
              if(confirm("Are you sire?")) {
                window.close();
              }
            }
            else {
                data[i]['submission'] = $(":checked").val();
                $("input").prop("checked", false);
                $.post('update.php', data[i], function(result) {
                  console.log(result)
                });
                // console.log(data[i]);
                i = i + 1;
                setinput(data, i);
                var vals = data[i]['submission']
                $("input[value='" + vals + "']").prop('checked', true)
            }
          });

          $('#prev').click(function() {
            i = i - 1;
            setinput(data, i)
            var vals = data[i]['submission']
            $("input[value='" + vals + "']").prop('checked', true)
          });
        })
      });
    </script>
  </body>
</html>
