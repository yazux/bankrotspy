<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-newspaper"></i> Новости площадок</h2>
                </div>
                <div class="contbody_forms">
                    <?if($outnews):?>
                    <?foreach($outnews as $otn): ?>
                    <table class="newsitem_table">
                        <tr>
                            <td style="width: 29px;">
                                <i class="icon-newspaper"></i>
                            </td>
                            <td>
                                <?=$otn['text']?><br/>
                                <span class="undtexttime">Время: <b><?=$otn['time']?></b>, <?=$otn['data']?></span>
                            </td>
                        </tr>
                    </table>
                    <hr class="hrnews" style="margin: 8px 5px 9px 30px;"/>
                    <?endforeach?>
                    <?else:?>
                    <br/>
                    Нет новостей<br/><br/>
                    <?endif?>
                </div>
                <div class="contfin_forms" style="padding: 8px 23px;">
                    <b>Всего:</b> <?=$total?>
                </div>
            </div>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>