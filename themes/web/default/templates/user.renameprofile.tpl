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


            <form name="mess" action="<?=$home?>/user/renameprofile?id=<?=$id?>" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i> Переименование поискового профиля</h2>
                    </div>
                    <div class="conthead">
                        <b>Название профиля:</b><br/>
                        <input type="text" name="profile_name" value="<?=$name?>" />
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Сохранить" />
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