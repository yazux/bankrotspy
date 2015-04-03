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

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-docs"></i>Написать отзыв/предложение</h2>
                </div>

                <?if($its_user):?>
                <form name="mess" action="<?=$home?>/feedback" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="contbody_forms">
                    Текст:<br/>
                    <?=func::tagspanel('messarea');?>
                    <div class="texta"><textarea id="messarea" name="mess" rows="5"></textarea></div>
                </div>
                <div class="contfin_forms">
                    <input type="submit" name="submit" value="<?=lang('send')?>" />
                </div>
                </form>
                <?else:?>
                    <div class="contbody_forms"><?=lang('reg_now')?></div>
                    <div class="contfin_forms"><br/></div>
                <?endif?>
            </div>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>/feedback">Отмена</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>