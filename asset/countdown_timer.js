var hr = 2;
var min = 0;
var sec = 0;

function formatlength(num) {
  if (String(num).length < 2) {
    return "0" + String(num);
  }
  else {
    return num;
  }
};

var t = document.getElementById("time");
function myfunc() {
  sec = sec - 1;
  if (sec == 0) {
    min = min - 1;
    sec = 59;
  }
  if (min == 0) {
    hr = hr - 1;
    min = 59;
    sec = 59;
  }
  t.innerHTML = formatlength(hr) + ":" +  formatlength(min) + ":" + formatlength(sec);
};

window.setInterval(myfunc, 1000);
