$(document).ready(setTimeout(function() {
  $('#successmsg').remove()
}, 1000));

$(document).ready(function() {
  $('li a').click(function() {
    $(this).addClass('active');
    $('li a').not(this).removeClass('active');
    if (($(this).attr('id')) == 'plink') {
      $('#profile').show();
      $('#userlist').hide();
      $('#questionlist').hide();
      $('#testlist').hide();
    }
    else if (($(this).attr('id')) == 'ulink') {
      $('#userlist').show();
      $('#profile').hide();
      $('#questionlist').hide();
      $('#testlist').hide();
    }
    else if (($(this).attr('id')) == 'qlink') {
      $('#questionlist').show();
      $('#profile').hide();
      $('#userlist').hide();
      $('#testlist').hide();
    }
    else if (($(this).attr('id')) == 'tlink') {
      $('#testlist').show();
      $('#questionlist').hide();
      $('#profile').hide();
      $('#userlist').hide();
    }
  })
});

// $(document).ready(function() {
//   $('table tbody tr td a').on('click', function() {
//     var link = $(this);
//     var data = link.parents("tr").html()
//     console.log(data);
//   })
// });
// $(document).ready(function() {
//   $('tr').click(function() {
//     $("td").wrapInner("<td><textarea rows=7></textarea></td>")
//     console.log('Clicked')
//   })
// });
