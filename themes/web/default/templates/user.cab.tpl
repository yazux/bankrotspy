<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-user-male"></i> Личный кабинет</h2>
                </div>
                <div class="contbody_forms">


                </div>
                <div class="contfin_forms">
                    <form name="mess" action="<?=$home?>/exit" method="POST">
                        <input type="hidden" name="act" value="do"/>
                        <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                        <input type="submit" value="<?=lang('exit')?>" />
                    </form>
                </div>
            </div>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/user/profile"><b>Мой профиль</b></a></div>
                <div class="elmenu"><a href="<?=$home?>/user/chpass"><?=lang('ch_pass')?></a></div>
                <div class="elmenu"><a href="<?=$home?>/user/chmail"><?=lang('ch_mail')?></a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>