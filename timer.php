<?php
  $hr = 1;
  $min = 30;
  $sec = 0;
  function format($num) {
    if (strlen($num) < 2) {
      print_r("0".$num);
      return "0".$num;
    }
    else {
      print_r($num);
      return $num;
    }
  }
  // format(5);

  function counter() {
    $hr = 1;
    $min = 30;
    $sec = 0;
    if ($sec == 0 && $min > 0) {
      $min = $min - 1;
      $sec = 59;
    }
    else if ($sec == 0 && $min == 0 && $hr > 0) {
      $hr = $hr - 1;
      $min = 59;
      $sec = 59;
    }
    $sec = $sec - 1;
    sleep(1);
    counter();
    // echo("<p>$hr : $min : $sec</p>");
  }
  counter();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <p><?= $hr, $min, $sec ?></p>
  </body>
</html>
