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
  location.hash='on_load_new_page';
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
      kmess: engine_settings.kmess
    },
    answer_load
  );

}

