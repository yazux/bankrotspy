<table>
    <tr>
        <td valign="top">
            <? if ($error): ?>
            <div class="error">
                <? foreach($error as $error): ?>
                <?= $error ?><br/>
                <? endforeach; ?>
            </div>
            <? endif; ?>

            <form name="mess" action="<?= $home ?>/control/edititem?id=<?= $item['id'] ?>" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i>Редактирование лота</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>Название лота:</b><br/>
                        <div class="texta"><textarea name="name" rows="4"><?= $item['description'] ?></textarea></div>
                    </div>
                    <div class="contbody_forms">
                        <b>Регион:</b><br/>
                        <select name="place">
                            <? $selected = '';?>
                            <? foreach($regions as $region): ?>
                                <? if($region['number'] == $item['place']): ?>
                                    <? $selected = 'selected'; ?>
                                <? else: ?>
                                    <? $selected = ''; ?>
                                <? endif; ?>
                            <option value="<?= $region['number'] ?>" <?= $selected ?>><?= $region['name'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Сохранить" />
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt"><?=lang('navigation')?>:</div>
                <div class="elmenu"><a href="<?=$home?>/card/<?= $item['id'] ?>">Вернуться</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>