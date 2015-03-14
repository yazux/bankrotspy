<table>
    <tr>
        <td valign="top">

            <?if ($error):?>
            <div class="error">
                <?foreach($error as $error): ?>
                <?=$error?><br/>
                <?endforeach?>
            </div>
            <?endif?>


            <form class='login_user_form' name='login_user' method="post" action="<?=$home?>/user/recpassword">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2>Восстановление пароля</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>E-mail указанный при регистрации:</b><br/>
                        <input name='mail' type="text" value=""/>
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('capcha')?>:</b><br/>
                        <?=$capcha?><br/>
                        <input style="width: 80px;" type="text" name="vcode" size="10" value=""/>
                    </div>
                    <div class="contfin_forms">
                        <input class='reg_form_butt' name="submit" type="submit" value="Продолжить" />
                    </div>
                </div>
            </form>


        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/login">Авторизация</a></div>
                <div class="elmenu"><a href="<?=$home?>/user/register"><?=lang('register')?></a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>