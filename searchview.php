<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="./asset/bootstrap/dist/css/bootstrap.min.css">
    <script type="text/javascript" src='./asset/jquery/jquery.min.js'></script>
    <script type="text/javascript" src="./asset/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row p-4">
        <div class="col-12">
          <form class="form-inline" action="index.html" method="post">
            <input class="form-control col-xs-12 col-md-9" type="search" name="question" value="" id='searchterm'>
            <button type="button" name="button" class="btn btn-small btn-primary col-xs-12 col-md-2 offset-sm-1"> <span class="fa fa-search"></span>Search</button>
          </form>
        </div>
        <div class="col-12 text-wrap" id='searchresult'>

        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    function gethits(result, term="") {
      var newdiv = "<div id='result' class='col-xs-12 col-md-9'></div>";
      $("#searchresult").append(newdiv);
      for (i in result) {
        var text = result[i]['text']
        if(text.toLowerCase().startsWith(term.toLowerCase()) && term.length > 0) {
          // newdiv.append(text);
          var href="edit.php?question=" + encodeURI(result[i]['question_id']) + "&edit=True";
          $("#result").append("<a class='list-group-item' href='" + href + "'>" + text + "</a>")

        }
        else {
          continue;
        }
      }
      $("#searchresult").append(newdiv);
      return
    }

    $(document).ready(function() {
      $.getJSON("search.php", function(data) {
        $("#searchterm").on("input", function() {
          $("#result").remove()
          var search_term = $("#searchterm").val();
          gethits(data, search_term)
      })
    })
  })
  </script>
</html>
