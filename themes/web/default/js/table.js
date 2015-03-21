//СНова костыль для осла, ну сколько можно!
JSON.stringify = JSON.stringify || function (obj) {

  var t = typeof (obj);
  if (t != "object" || obj === null) {

    // simple data type
    if (t == "string") obj = '"'+obj+'"';
    return String(obj);

  }
  else {
    // recurse array or object
    var n, v, json = [], arr = (obj && obj.constructor == Array);

    for (n in obj) {
      v = obj[n]; t = typeof(v);

      if (t == "string") v = '"'+v+'"';
      else if (t == "object" && v !== null) v = JSON.stringify(v);

      json.push((arr ? "" : '"' + n + '":') + String(v));
    }

    return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
  }
};

function load_table_page(page)
{
  engine_settings.page = page;
  $('html, body').animate({scrollTop: $('#on_load_new_page').offset().top}, 10);
  load_table();
}

//Постраничная навигация
function build_page_navigation(page, total, kmess)
{
  var outdata = '';
  var numpages = Math.ceil(total/kmess);
  var previos_page = page-1;
  var next_page = page+1;

  if(page == 1)
  {
    if(numpages > 2)
      outdata += '<span class="navpgin">1</span>';
    outdata += '<span class="navpgin">« Позже</span>';
  }
  else
  {
    if(numpages > 2)
      outdata += '<span class="navpg" onclick="load_table_page(1)">1</span>';
    outdata += '<span class="navpg" onclick="load_table_page(' + previos_page + ')">« Позже</span>';
  }

  outdata += '<form method="get" style="display:inline" action="">';
  outdata += '<select class="navselect" name="page" onchange="load_table_page(this.value)">';

  var i;
  for (i=1; i<=numpages; i++)
  {
    outdata += '<option value="' + i + '" ' + (i == page ? 'selected="selected"' : '' ) + ' >' + i + '</option>';
  }

  outdata += '</select></form>';

  if(page == numpages)
  {
    outdata += '<span class="navpgin">Раньше »</span>';
    if(numpages > 2)
      outdata += '<span class="navpgin">' + numpages + '</span>';
  }
  else
  {
    outdata += '<span class="navpg" onclick="load_table_page(' + next_page + ')">Раньше »</span>';
    if(numpages > 2)
      outdata += '<span class="navpg"  onclick="load_table_page(' + numpages + ')">' + numpages + '</span>';
  }

  if(numpages > 1)
  {
    outdata = '<div class="navig bs_index_table">' + outdata + '</div>';
    $('#navigation_container').html(outdata);
  }
  else
  {
    $('#navigation_container').html(' ');
  }
}

//Работа с данными
function begin_loader()
{
  if(engine_global_loader)
  {
    var mess = '<i class="icon-spin5"></i> Загрузка...';

    $("#loadmess").clearQueue();
    $("#loadmess").stop();
    $('#loadmess').fadeOut(1);

    $('#loadmess').html(mess);
    $('#loadmess').fadeIn(300);
  }
  else
    engine_global_loader = 1;
}

function end_loader()
{
  //$("#loadmess").clearQueue();
  //$("#loadmess").stop();
  //$('#loadmess').fadeIn(0);

  $('#loadmess').fadeOut(700);
}

function answer_load(data)
{
  //alert(data);
    if(data)
    {
        var obj = jQuery.parseJSON(data);

        //Заголовок таблицы
        var head_table = '';
        $.each(obj.columns, function() {

            //Стиль, если есть
            var style_holder = '';
            if(this.style)
                style_holder = 'style="' + this.style + '"';

            head_table += '<th ' + style_holder + ' >' + this.name + '</th>';

        });

        //данные таблицы
        var body_table = '';
        $.each(obj.maindata, function(key, val) {
            body_table += '<tr class="data_line">';

            $.each(this, function() {

              //Дополнительно (скрипты например)
              var addition_holder = '';
              if(this.addition)
                addition_holder = ' ' + this.addition + ' ';

              //Стиль, если есть
              var style_holder = '';
              if(this.style)
                style_holder = 'style="' + this.style + '"';

              body_table += '<td ' + style_holder + addition_holder +' >' + this.col + '</td>';
            });

            body_table += '</tr>';
        });

        var all_data = '<tr>' + head_table + '</tr>' + '' + body_table;
        $('.data_table').html(all_data);

        var start_int = ((engine_settings.page * engine_settings.kmess) - engine_settings.kmess)+1;
        var fin_int = (start_int + engine_settings.kmess - 1);
        if(fin_int > obj.count)
          fin_int = obj.count;
        var bar_info = 'Показаны результаты: '+ start_int +'-'+ fin_int +' из ' + obj.count;

        if(obj.count > 0)
        {
          //Верхняя панелька
          $('.top_nav_info').text(bar_info);
          $('.top_nav_info').fadeIn(0);

          //Нижняя панелька
          $('.bottomp_nav_info').text(bar_info);
          $('.bottomp_nav_info').fadeIn(0);
        }
        else
        {
          $('.top_nav_info').fadeOut(0);
          $('.bottomp_nav_info').fadeOut(0);
        }

        build_page_navigation(engine_settings.page, obj.count, engine_settings.kmess);

        end_loader();
    }
    else
    {
      end_loader();
    }
}

function create_head_mess(mess)
{
    $("#alltopmess").clearQueue();
    $("#alltopmess").stop();

    $('#alltopmess').text(mess);
    $('#alltopmess').fadeIn(400);
    $('#alltopmess').delay(4000).fadeOut(700);

}

function action_favorite(lot, action, item)
{
  if(action == 1)
  {
    //Удаляем из избранного
    $.post(
      "/tabledata/favorite",
      {
         itemid: lot,
         actionid: action,
         formid: engine_formid
      },
      function(data)
      {
        if(data == 'ok')
          create_head_mess('Лот был удален из изранного!');
        else
        {
          create_head_mess('Ошибка! Только для зарегистрированных пользователей!');
          $(item).find('i').attr('class', 'icon-star-clicked');
          $(item).find('i').attr('title', 'Удалить лот из избранного');
        }
      }
    );
  }
  else
  {
    //добавляем в избранное
    $.post(
      "/tabledata/favorite",
      {
        itemid: lot,
        actionid: action,
        formid: engine_formid
      },
      function(data)
      {
        if(data == 'ok')
          create_head_mess('Лот был добавлен в избранное!');
        else
        {
          create_head_mess('Ошибка! Только для зарегистрированных пользователей!');
          $(item).find('i').attr('class', 'icon-star-empty');
          $(item).find('i').attr('title', 'Добавить лот в избранное');
        }
      }
    );
  }
}

function listen_to_favorite(item)
{
  var item_id = $(item).attr('attr');
  var now_class = $(item).find('i').attr('class');

  if(now_class == 'icon-star-clicked')
  {
    //Кнопка уже нажата
    $(item).find('i').attr('class', 'icon-star-empty');
    $(item).find('i').attr('title', 'Добавить лот в избранное');
    action_favorite(item_id, 1, item);
  }
  else
  {
    //Кнопка не нажата
    $(item).find('i').attr('class', 'icon-star-clicked');
    $(item).find('i').attr('title', 'Удалить лот из избранного');
    action_favorite(item_id, 0, item);
  }
}


function connection_keeper()
{
  $.post(
    "/connectionkeeper",
    {
      formid: engine_formid
    },
    function(data)
    {
      //ничего не делаем, только сохраняем подключение
    }
  );
}



function save_settings_and_load()
{
  load_table();

  var json_set = JSON.stringify(engine_settings);

  $.post(
    "/tabledata/savesettings",
    {
      jsettings: json_set,
      formid: engine_formid
    },

    function(data)
    {
      //ничего не делаем
    }
  );
}

function compile_arr_set(obj)
{
  var string = '';
  var i =0;
  $.each(obj, function(key, val) {
    if(val = 1)
    {
      if(i != 0)
        string += '|';
      string += key;
      i++;
    }
  });
  return string;
}

function load_table()
{
  //Активная категория
  begin_loader();
  $('.table_tab td span').removeClass("active_tab");
  $('#bs_tab_' + engine_settings.category).addClass("active_tab");

  $.post(
    "/tabledata",
    {
      somevar: 'tvybunwedowhduw2397ey9hd8ybhb83wecugwvevct',
      formid: engine_formid,
      category: engine_settings.category,
      page: engine_settings.page,
      kmess: engine_settings.kmess,
      svalue: engine_settings.svalue,
      types: compile_arr_set(engine_settings.types)
    },
    answer_load
  );

}

function error_set_view(data)
{
  alert(data);
}

function search_listener()
{
  var error = 0;

  //Поиск
  var svalue = $('[name="svalname"]').val();
  if(svalue.length > 0 && svalue.length < 2)
  {
    error = 1;
    error_set_view('Длина строки должна быть больше 2-х символов!');
  }
  else
    engine_settings.svalue = svalue;

  //Типы
  var new_types = {};
  var choosen = 0;
  $.each(default_settings.types, function(key, val) {
    if($('[name="type_auct_' + key + '"]').prop('checked'))
    {
      new_types[key] = 1;
      choosen++;
    }
  });
  engine_settings.types = new_types;

  if(choosen < 1)
  {
    error = 1;
    error_set_view('Отметьте хотя бы один тип торгов!');
  }

  if(!error)
    save_settings_and_load();
}


function clean_set_listener()
{
  engine_settings.svalue = '';
  $('[name="svalname"]').val('');

  var new_types = {};
  $.each(default_settings.types, function(key, val) {
    $('[name="type_auct_' + key + '"]').prop('checked', true);
  });
  engine_settings.types = default_settings.types;

  //Cбрасываем страницу
  engine_settings.page = 1;

  save_settings_and_load();
}