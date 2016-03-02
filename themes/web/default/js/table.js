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

function columns_sort_listener(item)
{
  engine_settings.sortcolumn = $(item).attr('colattr');

  var sorttype = 0;
  var nowloaded = $(item).attr('class');

  if(nowloaded == 'no_sort')
  {

  }
  else
  {
    if (!nowloaded)
    {
      sorttype = 1;
      $(item).attr('class', 'sort_down');
    }
    if (nowloaded == 'sort_down')
    {
      sorttype = 2;
      $(item).attr('class', 'sort_up');
    }
    if (nowloaded == 'sort_up')
    {
      sorttype = 0;
      $(item).removeAttr('class');
    }

    engine_settings.sorttype = sorttype;

    save_settings_and_load();
  }
}

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
  var previos_page = parseInt(page)-1;
  var next_page = parseInt(page)+1;

  if(page == 1)
  {
    if(numpages > 2)
      outdata += '<span class="navpgin">1</span>';
    outdata += '<span class="navpgin">« Назад</span>';
  }
  else
  {
    if(numpages > 2)
      outdata += '<span class="navpg" onclick="load_table_page(1)">1</span>';
    outdata += '<span class="navpg" onclick="load_table_page(' + previos_page + ')">« Назад</span>';
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
    outdata += '<span class="navpgin">Вперед »</span>';
    if(numpages > 2)
      outdata += '<span class="navpgin">' + numpages + '</span>';
  }
  else
  {
    outdata += '<span class="navpg" onclick="load_table_page(' + next_page + ')">Вперед »</span>';
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
  if (engine_global_loader) {
    var mess = '<i class="icon-spin5"></i> Загрузка...';
    var el = $('#loadmess');
    
    var window_height = $(window).height();
    var window_width = $(window).width();
    var el_width = $(el).width();
    var el_height = $(el).height();
    
    var left = (window_width / 2) - (el_width / 2);
    var top = (window_height / 2) - (el_height / 2);
    
    $(el).css('left', left);
    $(el).css('bottom', top);
    
    $(el).clearQueue();
    $(el).stop();
    $(el).fadeOut(1);

    $(el).html(mess);
    $(el).fadeIn(100);
  } else {
    engine_global_loader = 1;
  }
}

function end_loader()
{
    $('#loadmess').fadeOut(700);
    $('.data_table').stickyTableHeaders('destroy');
    $(".data_table").stickyTableHeaders();
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

            var class_sort = '';
            if(this.nosort == 1)
            {
              class_sort = ' class="no_sort" ';
            }
            else
            {
              if (this.sorttype && engine_settings.sortcolumn == this.classname)
              {
                if (this.sorttype == 1)
                  class_sort = ' class="sort_down" ';
                if (this.sorttype == 2)
                  class_sort = ' class="sort_up" ';
              }
            }
            var addhtml_holder = '';
            if(this.addhtml)
              addhtml_holder = ' ' + this.addhtml + ' ';

            head_table += '<th ' + addhtml_holder + class_sort + ' colattr="' + this.classname + '" ' + style_holder + ' >' + this.name + '</th>';

        });

        //данные таблицы
        var body_table = '';
        $.each(obj.maindata, function(key, val) {
           
            var period  =   24 * 60 * 60 * 1000;
            var current = new Date().getTime() - period;
            var loaded = this.loadtime;
            var updated = this.last_update;
            var id = this.id;
            
            if (current < loaded) {
                body_table += '<tr class="data_line new" data-lotid="'+id+'">';
            } else if (current < updated) {
                body_table += '<tr class="data_line updated" data-lotid="'+id+'">';
            } else {
                body_table += '<tr class="data_line" data-lotid="'+id+'">';
            }

            delete this.id;
            delete this.loadtime;
            delete this.last_update
            
            $.each(this, function() {
    
              //Дополнительно (скрипты например)
              var addition_holder = '';
              if(this.addition)
                addition_holder = ' ' + this.addition + ' ';

              //Стиль, если есть
              var style_holder = '';
              if(this.style)
                style_holder = ' style="' + this.style + '" ';

              //Класс, если есть
              var customclass_holder = '';
              if(this.customclass)
                customclass_holder = ' class="' + this.customclass + '" ';

              body_table += '<td ' + customclass_holder + style_holder + addition_holder +' >' + this.col + '</td>';
            });

            body_table += '</tr>';
        });

        var all_data = '<thead><tr>' + head_table + '</tr></thead><tbody>'+ body_table+'</tbody>';
        $('.data_table').html(all_data);

        var start_int = ((engine_settings.page * engine_settings.kmess) - engine_settings.kmess)+1;
        var fin_int = (start_int + parseInt(engine_settings.kmess) - 1);
        if(fin_int > obj.count)
          fin_int = obj.count;
        var bar_info = 'Показаны результаты: '+ start_int +'-'+ fin_int +' из ' + obj.count;

        if(obj.count > 0)
        {
          //Верхняя панелька
          $('.top_nav_info .results').text(bar_info);
          $('.top_nav_info').fadeIn(0);

          //Нижняя панелька
          $('.bottomp_nav_info .results').text(bar_info);
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

function create_notify(mess) {
    
    var el = $("#alltopmess");
    
    $(el).clearQueue();
    $(el).stop();
    $(el).html(mess);
    
    var window_height = $(window).height();
    var window_width = $(window).width();
    var el_width = $(el).width();
    var el_height = $(el).height();
    
    var left = (window_width / 2) - (el_width / 2);
    var top = (window_height / 2) - (el_height / 2);
    
    $(el).css('left', left);
    $(el).css('bottom', top);
   
    $(el).fadeIn(100);
    $(el).delay(4000).fadeOut(700);

}

function action_favorite(lot, action, item, hide) {
    var data = {
        itemid: lot,
        actionid: action,
        formid: engine_formid
    };

    if(action == 1) {
        //Удаляем из избранного
        $.post("/tabledata/favorite", data, function(data) {
            if (data == 'ok') {
                create_notify('Лот был удален из избранного!');
                if(hide == true) {
                    
                    $('.data_table').find('[data-lotid='+lot+']').remove();
                }
            } else {
                create_notify('Данная функция доступна на платной подписке!');
                $(item).find('i').attr('class', 'icon-star-clicked');
                $(item).find('i').attr('title', 'Удалить лот из избранного');
            }
        });
    } else {
        //добавляем в избранное
        $.post("/tabledata/favorite", data, function(data) {
            if (data == 'ok') {
                create_notify('Лот был добавлен в избранное!');
            } else {
                create_notify('Данная функция доступна на платной подписке!');
                $(item).find('i').attr('class', 'icon-star-empty');
                $(item).find('i').attr('title', 'Добавить лот в избранное');
            }
        });
    }
}

function listen_to_favorite(item, hide)
{
    
  var item_id = $(item).attr('attr');
  var now_class = $(item).find('i').attr('class');

  if(now_class == 'icon-star-clicked')
  {
    //Кнопка уже нажата
    $(item).find('i').attr('class', 'icon-star-empty');
    $(item).find('i').attr('title', 'Добавить лот в избранное');
    action_favorite(item_id, 1, item, hide);
  }
  else
  {
    //Кнопка не нажата
    $(item).find('i').attr('class', 'icon-star-clicked');
    $(item).find('i').attr('title', 'Удалить лот из избранного');
    action_favorite(item_id, 0, item, hide);
  }
}

function connection_keeper() {
    var data = {
        formid: engine_formid
    }
    $.post("/connectionkeeper", data, function(data){//ничего не делаем, только сохраняем подключение
    });
}

function save_settings_and_load() {
    
    var json_set = JSON.stringify(engine_settings);
    var data = {
        jsettings: json_set,
        formid: engine_formid
    }
    
    $.post("/tabledata/savesettings", data, function(data) { });
    load_table();
}

function compile_arr_set(obj)
{
  var string = '';
  var i =0;
  $.each(obj, function(key, val) {
    if(val == 1)
    {
      if(i != 0)
        string += '|';
      string += key;
      i++;
    }
  });
  return string;
}

function date_to_int(date)
{
  if(date)
  {
    var date_sourse = date + '';
    var myDate = date_sourse.split('.');
    var newDate = myDate[1] + "/" + myDate[0] + "/" + myDate[2];
    return (new Date(newDate).getTime())/1000;
  }
  else
    return '';
}

function load_table() {
    //Активная категория
    begin_loader();
    
    var id = engine_settings.category;
    
    $('.table_tab td span').removeClass("active_tab");
    $('#bs_tab_' + id).addClass("active_tab");

    if(id == '-1') {
        $('.export').show();
    } else {
        $('.export').hide();
    }
    
    var data = {
        somevar: 'tvybunwedowhduw2397ey9hd8ybhb83wecugwvevct',
        formid: engine_formid,
        category: engine_settings.category,
        page: engine_settings.page,
        kmess: engine_settings.kmess,
        svalue: engine_settings.svalue,
        types: compile_arr_set(engine_settings.types),
        begin_date: date_to_int(engine_settings.begin_date),
        end_date: date_to_int(engine_settings.end_date),
        altint: engine_settings.altint,
        price_start: engine_settings.price_start,
        price_end: engine_settings.price_end,
        type_price: engine_settings.type_price,
        sortcolumn: engine_settings.sortcolumn,
        sorttype: engine_settings.sorttype,
        places: compile_arr_set(engine_settings.places),
        platforms: compile_arr_set(engine_settings.platforms),
        status: compile_arr_set(engine_settings.status),
        new_lots: engine_settings.new_lots
    }
    
    $.post("/tabledata", data, answer_load);

}

function search_listener()
{
  var error = 0;
  var str_err = '';

  //Поиск
  var svalue = $('[name="svalname"]').val();
  if(svalue.length > 0 && svalue.length < 2)
    str_err += 'Длина строки должна быть больше 2-х символов!' + '<br/>';
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

  //Статус торгов
  var new_status = {};
  var choosen_st = 0;
  $.each(default_settings.status, function(key, val) {
    if($('[name="status_auct_' + key + '"]').prop('checked'))
    {
      new_status[key] = 1;
      choosen_st++;
    }
  });
  engine_settings.status = new_status;
  if(choosen_st < 1)
    str_err += 'Выберите хотя бы один статус торгов!'+ '<br/>';


  //Дата начала конца торгов
  var begin_d = $('[name="begin_set_date"]').val();
  var end_d = $('[name="end_set_date"]').val();
  var begin_int_d = date_to_int(begin_d);
  var end_int_d = date_to_int(end_d);

  if(begin_int_d && end_int_d)
  {
    if(end_int_d < begin_int_d)
      str_err += 'Дата окончания не должна быть меньше даты начала!'+ '<br/>';
  }

    engine_settings.new_lots = 0;
    if($('[name="new_lots"]').prop('checked')) {
        engine_settings.new_lots = 1;
    }
      
  
  engine_settings.begin_date = begin_d;
  engine_settings.end_date = end_d;

  //Дней до торгов
  var altint = $('[name="altintconf"]').val();
  engine_settings.altint = altint;

  if((begin_d || end_d) && altint)
    str_err += 'Нельзя одновременно использовать "Дату подачи" и функцию "Дней до подачи заявок"!' + '<br/>';

  //Минимальная и максимальная цены
  var price_start = parseInt(unformat_number($('[name="price_start"]').val()));
  var price_end = parseInt(unformat_number($('[name="price_end"]').val()));
  engine_settings.price_start = price_start;
  engine_settings.price_end = price_end;

  if(price_start && price_end)
  {
    if(price_end < price_start)
      str_err += 'Конечная цена не может быть меньше начальной!'+ '<br/>';
  }

  //Регионы
  var choosen_regions = 0;
  $.each(default_settings.places, function(key, val) {
    if($('[name="regions[' + key + ']"]').prop('checked'))
    {
      choosen_regions++;
    }
  });
  if(choosen_regions < 1)
    str_err += 'Не выбрано не одного региона!'+ '<br/>';

  //Платформы
  var choosen_platforms = 0;
  $.each(default_settings.platforms, function(key, val) {
    if($('[name="platform_number_' + key + '"]').prop('checked'))
    {
      choosen_platforms++;
    }
  });
  if(choosen_platforms < 1)
    str_err += 'Не выбрано не одной платформы!'+ '<br/>';

  //По какой цене искать
  engine_settings.type_price = $('[name="type_price"]:checked').val();

  if(choosen < 1)
    str_err += 'Отметьте хотя бы один тип торгов!'+ '<br/>';

  if(!str_err)
  {
    engine_settings.page = 1;
    save_settings_and_load();
  }
  else
    create_notify(str_err);
}

function restore_settings()
{
  $('[name="svalname"]').val(engine_settings.svalue);

  $.each(default_settings.types, function(key, val) {
    $('[name="type_auct_' + key + '"]').prop('checked', false);
  });
  $.each(engine_settings.types, function(key, val) {
    $('[name="type_auct_' + key + '"]').prop('checked', true);
  });

  $.each(default_settings.status, function(key, val) {
    $('[name="status_auct_' + key + '"]').prop('checked', false);
  });
  $.each(engine_settings.status, function(key, val) {
    if(val==1)
       $('[name="status_auct_' + key + '"]').prop('checked', true);
  });

  $('[name="begin_set_date"]').val(engine_settings.begin_date);
  $('[name="end_set_date"]').val(engine_settings.end_date);

  $('[name="altintconf"]').val(engine_settings.altint);

  $('[name="price_start"]').val(engine_settings.price_start);
  $('[name="price_end"]').val(engine_settings.price_end);
  //Разделяем разряды
  number_format('price_start_forid');
  number_format('price_end_forid');

  $("input[name=type_price][value='" + engine_settings.type_price + "']").prop("checked",true);
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

    $.each(default_settings.status, function(key, val) {
        $('[name="status_auct_' + key + '"]').prop('checked', false);
    });
    
    var new_status = {};
    
    $.each(default_settings.status, function(key, val) {
        if(val==1)
            $('[name="status_auct_' + key + '"]').prop('checked', true);
    });
    engine_settings.status = default_settings.status;

    engine_settings.begin_date = '';
    engine_settings.end_date = '';
    $('[name="begin_set_date"]').val('');
    $('[name="end_set_date"]').val('');
    
    $('[name="new_lots"]').prop('checked',false);
    engine_settings.new_lots = 0;
  
    $('[name="altintconf"]').val('');
    engine_settings.altint = '';

    $('[name="price_start"]').val('');
    $('[name="price_end"]').val('');
    engine_settings.price_start = '';
    engine_settings.price_end = '';

    $("input[name=type_price][value='" + default_settings.type_price + "']").prop("checked",true);
    engine_settings.type_price = default_settings.type_price;

    $('#place_table input[type="checkbox"]').prop('checked', true);
    engine_settings.places = default_settings.places;
    place_set_listener();

    $('#platform_table input[type="checkbox"]').prop('checked', true);
    engine_settings.platforms = default_settings.platforms;
    platform_set_listener();

    //Cбрасываем страницу
    engine_settings.page = 1;

    save_settings_and_load();
}

function platform_set_listener()
{
  //Платформы
  var new_platforms = {};
  var choosen = 0;
  $.each(default_settings.platforms, function(key, val) {
    if($('[name="platform_number_' + key + '"]').prop('checked'))
    {
      new_platforms[key] = 1;
      choosen++;
    }
  });
  $('#total_platforms_set').text(choosen);
  engine_settings.platforms = new_platforms;
}

function listen_namepop(item)
{
   var lotid = $(item).attr('attr');
   var text_cont = $(item).text();
   if(text_cont == 'Показать')
   {
     $(item).text('Скрыть');
     $('#min_name_' + lotid).fadeOut(0);
     $('#max_name_' + lotid).fadeIn(700);
   }
   else
   {
     $(item).text('Показать');
     $('#max_name_' + lotid).fadeOut(0);
     $('#min_name_' + lotid).fadeIn(700);
   }
}

function place_set_listener()
{

    'use strict';
    //Регионы
    var new_places = {};
    var choosen = 0;
    
    if(typeof default_settings !== 'undefined') {
        $.each(default_settings.places, function(key, val) {
            if ($('[name="regions[' + key + ']"]').prop('checked')) {
                new_places[key] = 1;
                choosen++;
            }
        });
        $('#total_places_set').text(choosen);
        engine_settings.places = new_places;
    }
}

$(document).ready(function(){
  $(document).on('click', '.show_span', function(){
    listen_namepop(this);
  });

    $(document).on('click', '#icon_close_butt', function(){
        $('.popup_overlay').fadeOut(200);
    });

  $('.popup_overlay').click(function(event) {
    if ((event || window.event).target == this) {
      $('.popup_overlay').fadeOut(200);
    }
  });

  //Редактирование площадок
  $(document).on('click', '#platform_popup_close', function(){
    $('#popup_overlay_platform').fadeOut(200);
  });
  $(document).on('click', '#platform_set', function(){
    $('#popup_overlay_platform').fadeIn(200);
  });
  $(document).on('click', '#platform_table tr td label', function(){
    platform_set_listener();
  });
  $(document).on('click', '#platform_mark_all', function(){
    $('#platform_table input[type="checkbox"]').prop('checked', true);
    platform_set_listener();
  });
  $(document).on('click', '#platform_delete_all', function(){
    $('#platform_table input[type="checkbox"]').prop('checked', false);
    platform_set_listener();
  });

  //Редактирование регионов
    place_set_listener();
    
    $(document).on('click', '#region_popup_close', function(){
        $('#popup_overlay_region').fadeOut(200);
    });
  
    $(document).on('click', '#region_set', function(){
        $('#popup_overlay_region').fadeIn(200);
    });
  
    $(document).on('click', '#place_table tr td label', function(){
        place_set_listener();
    });
    
    $(document).on('click', '#region_mark_all', function(){
        $('#place_table input[type="checkbox"]').prop('checked', true);
        place_set_listener();
    });
  
    $(document).on('click', '#region_delete_all', function(){
        $('#place_table input[type="checkbox"]').prop('checked', false);
        place_set_listener();
    });

  //профили
  $(document).click(function(e)
  {
    if ($('.profiles_container').is(':hidden'))
    {
      if ($(e.target).closest('.pfame').length) {
        $('.profiles_container').fadeIn(200);
        $('#icon_s_status').html('<i class="icon-down-dir"></i>');
        return;
      }
      if ($(e.target).closest('#icon_s_status').length) {
        $('.profiles_container').fadeIn(200);
        $('#icon_s_status').html('<i class="icon-down-dir"></i>');
        return;
      }
    }
    else
    {
      if ($(e.target).closest(".profiles_container").length) return;
      $('.profiles_container').fadeOut(200);
      $('#icon_s_status').html('<i class="icon-right-dir"></i>');
      e.stopPropagation();
    }
  });

  //Создание профиля
  function create_new_profile()
  {
    var name = $('#name_new_profile').val();
    if(!name)
    {
      $('#error_newprofile_log').text('Поле пустое!');
    }
    else
    {
      $('#popup_overlay_newprofile').fadeOut(200);
      begin_loader();

      $.post(
        "/tabledata/newprofile",
        {
          formid: engine_formid,
          profile_name: name
        },
        answer_new_profile
      );
    }
  }

  function answer_new_profile(data)
  {
    end_loader();
    var answer = data.substr(0, 2);
    var loadid = data.substr(2);

    if(answer == 'ok')
      location.href = engine_home;
    else
      create_notify('Ошибка создания профиля!');
  }

  $(document).on('click', '#newprofile_popup_close', function(){
    $('#error_newprofile_log').text('');
    $('#popup_overlay_newprofile').fadeOut(200);
  });
  $(document).on('click', '#newprofile_set', function(){
    $('#popup_overlay_newprofile').fadeIn(200);
  });
  $(document).on('click', '#newprofile_popup_create', function(){
    create_new_profile();
  });

  //Удаление профиля
  $(document).on('click', '#del_now_ch_profile', function(){
    $('#popup_overlay_delprofile').fadeIn(200);
  });
  $(document).on('click', '#delprofile_popup_close', function(){
    $('#popup_overlay_delprofile').fadeOut(200);
  });

  $(document).on('click', '.profiles_container div', function(){

    var loadprofile = $(this).attr('attrid');

    $('.profiles_container').fadeOut(200);
    $('#icon_s_status').html('<i class="icon-right-dir"></i>');
    $('.pfame').text($(this).text());

    if(loadprofile)
    {
      begin_loader();

      $.post(
        "/tabledata/changeprofile",
        {
          formid: engine_formid,
          profile_id: loadprofile
        },

        function(data)
        {
          if(data == 'ok')
            location.href = engine_home;
          else
          {
            end_loader();
            create_notify('При смене профиля возникла ошибка!');
          }
        }
      );
    }

  });

  $(document).on('click', '#delprofile_doit', function(){
    $('#popup_overlay_delprofile').fadeOut(200);
    begin_loader();

    $.post(
      "/tabledata/deleteprofile",
      {
        formid: engine_formid,
        profile_id: engine_now_profile
      },

      function(data)
      {
        if(data == 'ok')
          location.href = engine_home;
        else
        {
          end_loader();
          create_notify('При удалении профиля возникла ошибка!');
        }
      }
    );

  });

});

$(function(){
    $('body').on('click', '.query_param_id', function(e){
        e.preventDefault();
        var _this = $(this);
        var param_id = $(this).parent().parent().attr('data-lotid');
        $.ajax({
            url:"tabledata/mp",
            method: "POST",
            data: {
                formid:engine_formid,
                id: param_id
            },
            success: function(html){
            //alert(html);
                if(html==""){
                    html="не определено";
                }
                $(_this).html(html);
            }
        });
    });
    
});