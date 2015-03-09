<table>
    <tr>
        <td>

            <?if ($error):?>
            <div class="error">
                <?foreach($error as $error): ?>
                <?=$error?><br/>
                <?endforeach?>
            </div>
            <?endif?>

            <form class='login_user_form' name='login_user' method="post" action="<?=$home?>/user/newpass?id=<?=$num?>">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2>Смена пароля</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>Новый пароль:</b><br/>
                        <input name='newname' type="password" value=""/>
                    </div>
                    <div class="contbody_forms">
                         <b><?=lang('pass_rep')?>:</b><br/>
                        <input name="name_rep" type="password"  value=""/>
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