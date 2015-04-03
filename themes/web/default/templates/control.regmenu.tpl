<table>
    <tr>
        <td valign="top">

            <form name="mess" action="<?=$home?>/control/regmenu" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i> <?=lang('text_reg')?></h2>
                    </div>
                    <div class="contbody_forms">
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="mess" rows="15"><?=$text?></textarea></div>
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Сохранить" />
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>