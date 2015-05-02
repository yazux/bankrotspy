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


            <form name="mess" action="<?=$home?>/user/newprofile" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i> Новый поисковой профиль</h2>
                    </div>
                    <div class="conthead">
                        <b>Название профиля:</b><br/>
                        <input type="text" name="profile_name" value="" />
                        <hr/>
                        <i class="icon-attention"></i> Буден создан новый чистый профиль с введенным названием.
                        При работе с этим профилем все изменения в поисковых параметрах будут сохранятся в этом поисковом профиле после нажатия кнопки "Искать".
                        Очистка полей будет работать только для выбранного профиля.
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Добавить" />
                    </div>
                </div>


        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/user/cab">Вернуться</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>