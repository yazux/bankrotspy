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

            <form name="mess" action="<?=$home?>/control/editcategory?id=<?=$id?>" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i>Редактирование категории лота</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>Название категории:</b><br/>
                        <input name="name" type="text" value="<?=$name?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>Ключевые слова</b> (каждое в новой строке)<br/>
                        <div class="texta"><textarea name="keywords_lot" rows="13"><?=$keywords?></textarea></div>
                    </div>
                    <div class="contbody_forms">
                        <b>Дополнительно</b> (каждое в новой строке)<br/>
                        <div class="texta"><textarea name="addition_lot" rows="13"><?=$addition?></textarea></div>
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Сохранить" />
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt"><?=lang('menu_settings')?>:</div>
                <div class="elmenu"><a href="<?=$home?>/control/categories">Вернуться</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>