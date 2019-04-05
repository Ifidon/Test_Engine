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
      $('#questionadd').hide();
    }
    else if (($(this).attr('id')) == 'ulink') {
      $('#userlist').show();
      $('#profile').hide();
      $('#questionadd').hide();
    }
    else if (($(this).attr('id')) == 'qalink') {
      $('#questionadd').show();
      $('#profile').hide();
      $('#userlist').hide();
    }
  })
});
// $(document).ready(function() {
//   $('#edit').click(function() {
//     $("td.small").wrapInner("<td><textarea rows=7></textarea></td>")
//   })
// })
