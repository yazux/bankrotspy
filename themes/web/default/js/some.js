function engine_mess(mess)
{
  $('#alltopmess').text(mess);
  $('#alltopmess').fadeIn(400);
  $('#alltopmess').delay(2000).fadeOut(700);
}

//Ужимаем картинки по ширине
function correct_images()
{
  var divwidth = $('.image_resizer').width()-14;
  $('.insimg').css({'max-width' : divwidth});
}

// Анимация и прочая бойда

$(document).ready(function(){

  correct_images();

});

function insertnick(nickname)
{
  document.mess.msg.focus();
  document.mess.msg.value+= '' + nickname + ', ';
}

function insertquote(nickname, quote)
{
  document.mess.msg.focus();
  if (quote)
  {
    quote = quote.replace('&apos;', "'");
  }
  document.mess.msg.value+= '[c='+ nickname +']' + quote + '[/c]' + "\n";
}

function scrollWidth()
{
  var div = $('<div>').css({
    position: "absolute",
    top: "0px",
    left: "0px",
    width: "100px",
    height: "100px",
    visibility: "hidden",
    overflow: "scroll"
  });

  $('body').eq(0).append(div);
  var width = div.get(0).offsetWidth - div.get(0).clientWidth;
  div.remove();
  return width;
}


function get_scroll(a)
{
  var d = document,
    b = d.body,
    e = d.documentElement,
    c = "client" + a;
  a = "scroll" + a;
  return /CSS/.test(d.compatMode)? (e[c]< e[a]) : (b[c]< b[a])
}

