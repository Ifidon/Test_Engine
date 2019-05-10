<?php
  session_start();
  $_SESSION['test'] = 1
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SAMPLE</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <a href="#">Try It</a>
    <script type="text/javascript">
      $("a").click(function() {
        $.getJSON('testform.php', function(data) {
          console.log(data)
          for(i in data) {
            $("body").append("<div id='content'></div>");
            $("#content").html(data[i]);
          }
        })
      })
    </script>
  </body>
</html>
