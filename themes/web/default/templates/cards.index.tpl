<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> Лот №<?=$lotnumber?></h2></div>

                <div class="contbody_forms">
                    <b>Название лота:</b><br/>
                    <?=$lotdescr?>
                    <hr style="margin: 7px 0"/>
                    <i class="icon-address"></i><b><?=$lotregion?></b> &nbsp;&nbsp;&nbsp;
                    <span class="icon_to_click_fav" attr="<?=$id?>">
                        <?if($lotfav):?>
                            <i class="icon-star-clicked"></i><span id="fav_info">Удалить лот из избранного</span>
                        <?else:?>
                            <i class="icon-star-empty"></i><span id="fav_info">Добавить лот в избранное</span>
                        <?endif?>
                    </span>
                </div>
                <div class="contfin_forms_delimiter">

                </div>
                <div class="contbody_forms">

                    <table class="lottable">
                        <tr>
                            <td style="width: 200px;"><b>Цена лота:</b><br/></td>
                            <td><i class="icon-rouble"></i> <?=$lotprice?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Текущая цена:</b><br/></td>
                            <td><i class="icon-rouble"></i> <?=$lotprice?></td>
                        </tr>
                    </table>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 200px;"><b>Тип торгов:</b><br/></td>
                            <td><?=$lottype?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Статус торгов:</b><br/></td>
                            <td><?=$lotstatus?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Дата начала торгов:</b><br/></td>
                            <td><?=$lotstarttime?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Дата окончания торгов:</b><br/></td>
                            <td><?=$lotendtime?></td>
                        </tr>
                    </table>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 200px;"><b>Должник:</b><br/></td>
                            <td><?=$debtor?>, дело № <?=$case_number?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Организатор торгов:</b><br/></td>
                            <td><?=$organizer?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Контактное лицо организатора торгов:</b><br/></td>
                            <td><?=$contact_person?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Арбитражный управляющий:</b><br/></td>
                            <td><?=$manager?></td>
                        </tr>
                    </table>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 200px;"><b>Номер торгов:</b><br/></td>
                            <td><?=$code_torg?></td>
                        </tr>
                        <tr>
                            <td style="width: 200px;"><b>Торги на площадке:</b><br/></td>
                            <td><a target="_blank" href="<?=$auct_link?>"><i class="icon-globe-table"></i><?=$platform_url?></a></td>
                        </tr>

                    </table>

                </div>
            </div>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>

<script type="text/javascript">
    var engine_formid = <?=core::$formid?>;

    function action_favorite_cards(lot, action, item)
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
                            $('#fav_info').text('Удалить лот из избранного');
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
                            $('#fav_info').text('Добавить лот в избранное');
                        }
                    }
            );
        }
    }

    function listen_to_favorite_cards(item)
    {
        var item_id = $(item).attr('attr');
        var now_class = $(item).find('i').attr('class');

        if(now_class == 'icon-star-clicked')
        {
            //Кнопка уже нажата
            $(item).find('i').attr('class', 'icon-star-empty');
            $('#fav_info').text('Добавить лот в избранное');
            action_favorite_cards(item_id, 1, item);
        }
        else
        {
            //Кнопка не нажата
            $(item).find('i').attr('class', 'icon-star-clicked');
            $('#fav_info').text('Удалить лот из избранного');
            action_favorite_cards(item_id, 0, item);
        }
    }


    $(document).on('click', '.icon_to_click_fav', function(){
        listen_to_favorite_cards(this);
    });
</script>