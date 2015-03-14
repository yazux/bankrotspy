<table >
    <tr>
        <td valign="top">

            <?if ($error):?>
            <div class="error">
                <?foreach($error as $error): ?>
                <?=$error?><br/>
                <?endforeach?>
            </div>
            <?endif?>

            <form name="mess" action="<?=$home?>/support/newticket" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2>Новый запрос в техподдержку</h2>
                    </div>
                    <div class="contbody_forms">
                        Пожалуйста, подробно изложите проблему возникающую на сайте. Если возможно, выложите скриншот экрана на файлообменник и приложите ссылку.
                        <br/>Так же если у вас есть предложения, замечания по работе сайта, подробно опишите что вы бы хотели добавить, изменить на сайте, что бы работа с ним бала максимально комфортной.
                    </div>
                    <div class="contbody_forms">
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="mess" rows="5"></textarea></div>
                    </div>

                    <div class="contfin_forms">
                        <input class='reg_form_butt' type="submit" name="submit" value="Добавить" />
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Техподдержка</div>
                <div class="elmenu"><a href="<?=$home?>/support">Вернуться</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
