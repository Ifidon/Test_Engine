$(document).ready(function() {
  var hr = Number();
  var min = Number();
  var sec = Number();
  $.getJSON("duration.php", function(data) {
    // window.console && console.log(data)
    time = Number(data['duration']);
    hr = parseInt((time)/60);
    min = time % 60
    var sec = 0;
    console.log(hr);
    console.log(min);
  })

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
  window.setInterval(counter, 1000);
})
