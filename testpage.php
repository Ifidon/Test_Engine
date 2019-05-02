<?php
  session_start();
  require 'unauthorized.php';
  require 'pdo.php';
  // SELECT * FROM test_questions JOIN question ON test_questions.question_id = question.question_id where test_id = :id
  $query = $pdo->prepare("SELECT COUNT(question_id), grp_category FROM test_questions JOIN question_grp ON test_questions.grp_id = question_grp.grp_id where test_questions.test_id = :id GROUP BY test_questions.grp_id");
  $query->execute(array(
    ':id' => htmlentities($_GET['test'])
  ));

  $test = $query->fetchAll(PDO::FETCH_ASSOC);
  print_r(json_encode($test));

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Test Introduction</title>
  </head>
  <body>
      
  </body>
</html>
