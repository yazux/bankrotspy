<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-docs"></i> <?=$name?></h2>
                    <a class="button" style="float:right; margin-top: -25px; margin-left: 10px;" href="/assistance">
                        Помощь в торгах
                    </a>
                    <a href="<?= core::$home ?>/request" class="button" style="float:right; margin-top: -25px;" target="_blank">
                        Заказать ЭЦП/КЦП 
                    </a>
                </div>
                <div class="contbody_forms">
                    !<?=$text?>
                </div>
            </div>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <?if(core::$rights==100):?>
                <div class="elmenu"><a href="<?=$home?>/control/pages">Все страницы</a></div>
                <?endif?>
                <?if($r_edit):?>
                <div class="elmenu"><a href="<?=$home?>/pages/statedit?id=<?=$id?>">Редактировать страницу</a></div>
                <div class="elmenu"><a href="<?=$home?>/pages/statdel?id=<?=$id?>">Удалить страницу</a></div>
                <?endif?>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>