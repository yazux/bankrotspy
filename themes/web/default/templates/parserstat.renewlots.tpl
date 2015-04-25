<table>
    <tr>
        <td valign="top">

            <div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
                <div class="conthead">
                    <table>
                        <tr>
                            <td>
                                <b>Перезаливка лотов...</b><br/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="contbodytext">
                    <center>
                        <span id="hide_onres">
                            Не закрывайте это окно до концы выполнения операции!
                            <hr style="margin-bottom: 10px;"/>
                        </span>
                        <span id="not_res"><b>Выполнено:</b>  <span id="now_count">0</span> из <?=$total?></span>
                    </center>
                </div>
                <div class="contfintext">
                    <center>
                        <a class="urlbutton" href="<?=$home?>/parserstat/errlots">Вернуться</a>
                    </center>
                </div>
            </div>
            <div id="all_lots" style="display: none"><?=$lots?></div>
        </td>
        <td class="right_back_menu">
            <? temp::include('parsersstat.index.right.tpl') ?>
        </td>
    </tr>
</table>

<script type="text/javascript">
    var all_lots = jQuery.parseJSON($('#all_lots').text());
    var sup_counter = 0;
    var now_counter = 0;
    var now_index;
    var napikey = 'sda45hvQE34caCBeSDergDFENerE';
    var now_answer = '';

    var alllog = '';

    function onres()
    {
        now_counter++;
        $('#now_count').text(now_counter);

        //alllog = alllog + "\n" + now_answer;

        var next = now_index + 1;

        if (all_lots[next])
            loc_query(next);
        else
        {
            //alert(alllog);
            $('#hide_onres').hide();
            $('#not_res').html('<b>Готово!</b><br/>В основную таблицу залито ' + sup_counter + ' лотов из <?=$total?>.');
        }
    }

    function delete_query(index)
    {
        $.post('<?=$home?>/api/1.0/bcdata/delfrombad',
                {
                    apikey: napikey,
                    delid: all_lots[index].id
                }
                , function(data)
                {
                    now_index = index;
                    if(data == 'ok')
                        onres();
                    else
                        onres();
                }
        );
    }

    function loc_query(index)
    {
        $.post('<?=$home?>/api/1.0/bcdata/loaditem',
                {
                    apikey: napikey,
                    itemdata: all_lots[index].data,
                    unicode: 'unicode',
                    dontadderrors: 'dontadderrors'
                }
                , function(data)
                {
                    now_answer = data;
                    now_index = index;
                    if(data == 'ok')
                    {
                        sup_counter++;
                        delete_query(index);
                    }
                    else
                        onres();
                }
        );
    }

    function main_lots_query()
    {
        loc_query(0);
    }


    main_lots_query();
</script>