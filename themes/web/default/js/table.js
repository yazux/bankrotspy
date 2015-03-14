
function answer_load(data)
{
    if(data)
    {
        var obj = jQuery.parseJSON(data);

        //Заголовок таблицы
        var head_table = '';
        $.each(obj.columns, function(key, val) {
            head_table += '<th>' + val + '</th>';
        });

        //данные таблицы
        var body_table = '';
        $.each(obj.maindata, function(key, val) {
            body_table += '<tr class="data_line">';

            $.each(this, function() {
              var attr = '';
              if(this.attr)
                attr = 'attr="' + this.attr + '"';

              var class_holder = '';
              if(this.class)
                class_holder = 'class="' + this.class + '"';

              body_table += '<td ' + attr + class_holder +' >' + this.col + '</td>';
            });

            body_table += '</tr>';
        });

        var all_data = '<tr>' + head_table + '</tr>' + '' + body_table;
        $('.data_table').html(all_data);
    }
}

function load_table()
{
  $.post(
    "/tabledata",
    {  },
    answer_load
  );
}

