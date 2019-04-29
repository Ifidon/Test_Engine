<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Countdown</title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/styles/countdown.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div>
      <div class="container">
        <div class="form-group m-5" id='timer_input'>
          <div class="form form-inline m-auto">
            <input type="text" name="hr" value="" id="hr" class="form-control m-1" placeholder="Enter Hours">
            <input type="text" name="min" value="" id="min" class="form-control m-1" placeholder="Enter Minutes">
            <input type="text" name="sec" value="" id="sec" class="form-control m-1" placeholder="Enter Seconds">
            <input type="submit" name="start" value="Start Timer" class="btn btn-secondary btn-sm m-1" id="start">
          </div>
        </div>
        <div class="float-right m-5" id="timer">
          <span id="hrdisp"></span>
          <span id="mindisp"></span>
          <span id="secdisp"></span>
        </div>
      </div>

    </div>
    <script type="text/javascript">
      var hr = 0;
      var min = 0;
      var sec = 0;
      function num_formatter(num) {
        num = String(num);
        if (num.length < 2) {
          return "0" + num;
        }
        else {
          return num;
        }
      }

      function counter() {
        if (sec == 0 && min > 0) {
          min = min - 1;
          sec = 59;
        }
        else if (sec == 0 && min == 0 && hr > 0) {
          hr = hr - 1;
          min = 59;
          sec= 59;
        }
        else if (sec == 0 && min == 0 && hr == 0) {
          this.window.close();
        }

        sec = sec - 1;
        $("#hrdisp").html(num_formatter(hr) + " Hrs : ");
        $("#mindisp").html(num_formatter(min) + " Mins : ");
        $("#secdisp").html(num_formatter(sec) + " Secs");

      }
      $(document).ready(function() {
        console.log("Document Ready");
        $("#start").click(function() {
          $("#timer_input").hide();
          hr = $("#hr").val();
          min = $("#min").val()
          sec = $("#sec").val();
          window.setInterval(counter, 1000);
          // num_formatter($("#hr").val());
        })
      })

    </script>
  </body>
</html>
