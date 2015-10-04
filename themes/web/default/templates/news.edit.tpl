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

            <form name="mess" action="<?=$home?>/news/edit?id=<?=$id?>" method="post" enctype="multipart/form-data">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>

                <div class="content">
                    <div class="conthead"><h2><i class="icon-newspaper"></i> Редактирование новости</h2></div>

                    <div class="contbody_forms">
                        <b>Заголовок новости:</b><br/>
                        <input name='newshead' class='login_user_input' type="text" size="60" value="<?=$nick?>"/><br/>
                    </div>

                    <div class="contbody_forms">
                        <b>Ключевые слова (keywords):</b><br/>
                        <input name="keywords" type="text"  value="<?=$keywords?>"/><br/>
                    </div>
                    <div class="contbody_forms">
                        <b>Описание (description):</b><br/>
                        <input name="description" type="text"  value="<?=$description?>"/><br/>
                    </div>
                    
                    <div class="contbody_forms">
                        <b>Текст:</b>
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="mess" rows="15"><?=$text?></textarea></div>
                    </div>

                    <div class="contfintext">
                        <input name="submit" type="submit" value="Сохранить" />
                    </div>
                </div>
            </form>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>/news/view?id=<?=$id?>">Отмена</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>