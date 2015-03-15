
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

              //Стиль, если есть
              var style_holder = '';
              if(this.style)
                style_holder = 'style="' + this.style + '"';

              body_table += '<td ' + style_holder +' >' + this.col + '</td>';
            });

            body_table += '</tr>';
        });

        var all_data = '<tr>' + head_table + '</tr>' + '' + body_table;
        $('.data_table').html(all_data);
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

function answer_favorite_deteted(data)
{
  if(data == 'ok')
    create_head_mess('Лот был удален из изранного!');
  else
    create_head_mess('Ошибка! Только для зарегистрированных пользователей!');
}

function answer_favorite_added(data)
{
  if(data == 'ok')
    create_head_mess('Лот был добавлен в избранное!');
  else
    create_head_mess('Ошибка! Только для зарегистрированных пользователей!');
}

function action_favorite(lot, action)
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
      answer_favorite_deteted
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
      answer_favorite_added
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
    action_favorite(item_id, 1);
  }
  else
  {
    //Кнопка не нажата
    $(item).find('i').attr('class', 'icon-star-clicked');
    $(item).find('i').attr('title', 'Удалить лот из избранного');
    action_favorite(item_id, 0);
  }
}


function load_table()
{
  $.post(
    "/tabledata",
    {
      formid: engine_formid
    },
    answer_load
  );
}

