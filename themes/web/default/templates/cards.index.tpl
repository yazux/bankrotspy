<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-newspaper"></i> Лот №<?=$lotnumber?></h2>
                    <a href="<?= core::$home ?>/assistance?lotid=<?= $id ?>" class="button aright">
                        Помощь и участие в торгах
                    </a>
                </div>

                <div class="contbody_forms">
                    <b>Название лота:</b><br/>
                    <?=$lotdescr?>
                    <hr style="margin: 7px 0"/>
                    <i class="icon-location"></i><b><?=$lotregion?></b> &nbsp;&nbsp;&nbsp;
                    <span class="icon_to_click_fav" attr="<?=$id?>">
                        <?if($lotfav):?>
                            <i class="icon-star-clicked"></i><span id="fav_info">Удалить лот из избранного</span>
                        <?else:?>
                            <i class="icon-star-empty"></i><span id="fav_info">Добавить лот в избранное</span>
                        <?endif?>
                    </span>
                    <? if(core::$rights == 100): ?>
                    <a href="<?= core::$home ?>/control/edititem?id=<?= $id ?>" class="edit aright">Изменить</a>
                    <? endif; ?>
                </div>
                <? if($lotfav): ?>
                <div class="contbody_forms">
                     <table class="lottable">
                        <tr>
                            <td style="width: 60px;"><b contenteditable="true">Комментарий:</b><br/></td>
                            <td width="100%"><textarea class="nostyle"><?= $note ?></textarea></td>
                            <td><a class="button">Сохранить</a></td>
                        </tr>
                     </table>
                 </div>
                 <style>

                    .fa {
                        color:#d27600;
                    }
                    .fa:hover {
                        color:red;
                        cursor:pointer;
                    }
                    textarea {
                        background:none;
                        height:20px;
                    }
                    .style{
                        border:1px solid #ebebeb;
                        resize:vertical;
                    }
                    .nostyle {
                        border:none;
                        resize:none;
                    }
                    .lottable .button {
                        display:none;
                    }
                 </style>
                 <script>
                    $('.lottable').on('click', 'textarea', function(){
                        $(this).removeClass('nostyle');
                        $(this).addClass('style');
                        $('.lottable .button').show();
                    });
                    $('.lottable').on('click', '.button', function(){
                        $(this).hide();
                        var input = $('.lottable textarea');
                        
                        $(input).removeClass('style');
                        $(input).addClass('nostyle');

                        if ($(input).val().length > 0) {
                            var action = 'save';
                        } else {
                            var action = 'delete';
                        }
                        
                        var data = {
                            formid:<?= core::$formid ?>,
                            id:<?= $id ?>,
                            text:$(input).val()
                        };
                        $.ajax({
                            method: 'POST',
                            url: '/tabledata/note?action='+action,
                            dataType: 'json',
                            data: data,
                            success: function(result){
                                console.log(result);
                            }
                        });
                    });
                 </script>
                <? endif; ?>
                <div class="contfin_forms_delimiter">

                </div>
                <div class="contbody_forms">

                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Цена лота:</b><br/></td>
                            <td><i class="icon-rouble"></i> <?=$lotprice?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Текущая цена:</b><br/></td>
                            <td><i class="icon-rouble"></i> <?=$nowprice?>

                                <?if($pricediff AND $pricediff != '-'):?>
                                (<?=$pricediff?>%)
                                <?endif?>
                            </td>
                        </tr>
                    </table>
                    <?if($needshow_add_price):?>
                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Рыночная цена:</b><br/></td>
                            <td><i class="icon-rouble"></i> <?=$realprice?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Шаг цены:</b><br/></td>
                            <td>5%</td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b> Задаток:</b><br/></td>
                            <td>10%</td>
                        </tr>
                        <? if(!empty($market_price)): ?>
                            <?= $market_price ?>
                        <? endif; ?>
                        <tr>
                            <td style="width: 300px;"><b>Доход:</b><br/></td>
                            <td><i class="icon-rouble"></i> <?=$profitrub?>

                            <?if($profitproc AND $profitproc != '-'):?>
                              (<?=$profitproc?>%)
                            <?endif?>
                            </td>
                        </tr>
                    </table>
                    <?endif?>
                    <?if($needshow_deb_points):?>
                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Баллы:</b><br/></td>
                            <td <?=$customclassdeb?> <?=$additionhtmldeb?> > <?=$debpoints?></td>
                        </tr>
                    </table>
                    <?endif?>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Категория:</b><br/></td>
                            <td><?if($category):?><?=$category?><?else:?>Прочее<?endif?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Тип торгов:</b><br/></td>
                            <td><?=$lottype?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Статус торгов:</b><br/></td>
                            <td><?=$lotstatus?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Дата начала подачи заявок:</b><br/></td>
                            <td><?=$lotstarttime?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Дата окончания подачи заявок:</b><br/></td>
                            <td><?=$lotendtime?></td>
                        </tr>
                    </table>
                    <? if(!empty($schedule)): ?>
                    <hr/>
                    <table>
                    <?= $schedule ?>
                    </table>
                    <style>
                    .data.inner {
                        
                        border-collapse:collapse;
                    }
                    .data.inner td {
                        border:1px solid #ebebeb;
                        text-align:center;
                    }
                    </style>
                    <? endif; ?>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Банкрот:</b><br/></td>
                            <td>
                                <?if($debtor):?>
                                    <?if($debtor_profile):?>
                                        <a target="_blank" href="<?=$debtor_profile?>"><i class="icon-globe-table"></i>
                                            <?=$debtor?>
                                        </a>
                                    <?else:?>
                                        <?=$debtor?>
                                    <?endif?>
                                <?else:?>
                                    <span style="color:#95968d">нет</span>
                                <?endif?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Дело №:</b><br/></td>
                            <td><?=$case_number?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>ИНН банкрота:</b><br/></td>
                            <td><?if($debtor_inn):?><?=$debtor_inn?><?else:?><span style="color:#95968d">нет</span><?endif?></td>
                        </tr>
                    </table>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Организатор торгов (ссылка на карточку на федресурсе):</b><br/></td>
                            <td>
                                <?if($organizer):?>
                                    <?if($organizer_profile):?>
                                        <a target="_blank" href="<?=$organizer_profile?>"><i class="icon-globe-table"></i>
                                        <?=$organizer?>
                                        </a>
                                    <?else:?>
                                        <?=$organizer?>
                                    <?endif?>
                                <?else:?>
                                    <span style="color:#95968d">нет</span>
                                <?endif?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Контактное лицо организатора торгов:</b><br/></td>
                            <td><?if($contact_person):?><?=$contact_person?><?else:?><span style="color:#95968d">нет</span><?endif?></td>
                        </tr>
                        <tr>
                            <td style="width: 300px; background:#eee;"><b>Арбитражный управляющий:</b><br/></td>
                            <td style="background:#eee;">
                                <? if($manager): ?>
                                    <a target="_blank" href="<?= core::$home ?>/amc/<?= $oid ?>"><i class="icon-globe-table"></i>
                                    <?= $manager ?>
                                    </a>
                                <? else: ?>
                                  <span style="color:#95968d">нет</span>
                                <? endif ?>
                                
                                <? //if($manager && $arbitr_profile): ?>

                                    <? if($rating > 5): ?>
                                        <? $class = 'class="plus"'; ?>
                                    <? else: ?>
                                        <? $class = 'class="minus"'; ?>
                                    <? endif; ?>
                                        &nbsp;&nbsp;&nbsp;Рейтинг:&nbsp;&nbsp;<a <?= $class ?> href="<?= core::$home ?>/amc/<?= $oid ?>" target="_blank"><?= $rating ?></a>
                                <? //endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>ИНН организатора:</b><br/></td>
                            <td><?if($inn_orgname):?><?=$inn_orgname?><?else:?><span style="color:#95968d">нет</span><?endif?></td>
                        </tr>
                    </table>
                    <hr/>
                    <table class="lottable">
                        <tr>
                            <td style="width: 300px;"><b>Номер торгов:</b><br/></td>
                            <td>
                                <? if($code_torg !== '-1'): ?>
                                    <?=$code_torg?>
                                <? else: ?>
                                    <i class="fa fa-lock"></i>
                                <? endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Торги на площадке:</b><br/></td>
                            <td>
                                <? if($auct_link !== '-1'): ?>
                                <a target="_blank" href="<?=$auct_link?>"><i class="icon-globe-table"></i><?=$platform_url?></a>
                                <? else: ?>
                                <i class="fa fa-lock"></i>
                                <? endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Лот на федресурсе:</b><br/></td>
                            <td>
                                <? if($fedlink !== '-1'): ?>
                                    <? if($fedlink): ?>
                                    <a target="_blank" href="<?= $fedlink ?>"><i class="icon-globe-table"></i>fedresurs.ru</a>
                                    <? else: ?>
                                    нет
                                    <? endif; ?>
                                <? else: ?>
                                    <i class="fa fa-lock"></i>
                                <? endif; ?>
                            </td>
                        </tr>
                    </table>
                    <? if(!empty($similarDataPrice) && $similarDataPrice !== 'access'): ?>
                    <table class="lottable">
                        <tr>
                        <td>
                            <div id="grapch"></div>
                        </td>
                        </tr>
                    </table>
                    <? elseif($similarDataPrice == 'access'): ?>
                    <table class="lottable">
                        <tr>
                        <td>
                            <div id="grapch"><center><strong style="font-size:18px;">
                                <? if($graphType == 1): ?>
                                Цены на аналогичные товары
                                <? else: ?>
                                Цена 1м2 аналогичных предложений
                                <? endif; ?>
                            </strong></center></div>
                        </td>
                        </tr>
                        <tr>
                            <td>
                            <center>Информация доступна на тарифном плане VIP</center>
                            </td>
                        </tr>
                    </table>
                    <? endif; ?>
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
<? if(!empty($similarDataPrice) && $similarDataPrice !== 'access'): ?>
<style>
#grapch {
	width		: 100%;
	height		: 500px;
	font-size	: 11px;
}	

.amcharts-graph-graph2 .amcharts-graph-stroke {
  stroke-dasharray: 4px 5px;
  stroke-linejoin: round;
  stroke-linecap: round;
  -webkit-animation: am-moving-dashes 1s linear infinite;
  animation: am-moving-dashes 1s linear infinite;
}

@-webkit-keyframes am-moving-dashes {
  100% {
    stroke-dashoffset: -28px;
  }
}
@keyframes am-moving-dashes {
  100% {
    stroke-dashoffset: -28px;
  }
}
	
</style>
<script src="http://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="http://www.amcharts.com/lib/3/serial.js"></script>
<script src="http://www.amcharts.com/lib/3/themes/light.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/lang/ru.js"></script>

<script type="text/javascript">
    var chart = AmCharts.makeChart("grapch", {
  "type": "serial",
  "titles": [{
        <? if($graphType == 1): ?>
        "text": "Цены на аналогичные товары",
        <? else: ?>
        "text": "Цена 1м2 аналогичных предложений",
        <? endif; ?>
        "size": 15
    }],
  
    "theme": "light",

   
    "marginRight": 8,
    "marginTop": 10,
    "marginBottom": 26,
    "balloon": {
        "adjustBorderColor": false,
        "horizontalPadding": 10,
        "verticalPadding": 8,
        "color": "#ffffff"
    },

    "dataProvider": <?= $similarDataPrice ?>,
  "valueAxes": [{
    "axisAlpha": 0,
    "position": "left"
  }],
  "startDuration": 1,
  "graphs": [{
    "alphaField": "alpha",
    "balloonText": "<span style='font-size:12px;'>[[title]]:<br>[[value]] руб.</span>",
    "fillAlphas": 1,
    "title": "Цена",
    "type": "column",
    "valueField": "price",
    "dashLengthField": "dashLengthColumn"
  }, {
    "id": "graph2",
    "balloonText": "<span style='font-size:12px;'>[[title]]: [[value]] руб.</span>",
    "bullet": "round",
    "lineThickness": 3,
    "bulletSize": 7,
    "bulletBorderAlpha": 1,
    "bulletColor": "#FFFFFF",
    "useLineColorForBulletBorder": true,
    "bulletBorderThickness": 3,
    "fillAlphas": 0,
    "lineAlpha": 1,
    "title": "Средняя цена",
    "valueField": "average"
  }],
  "categoryField": "price",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "tickLength": 0
  }
});
</script>
<? endif; ?>

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