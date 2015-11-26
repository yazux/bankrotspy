<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Шаблоны писем</h2>
                </div>
                <div class="contbody_forms">
                Доступные теги:<br/>
                {name} - Логин
                {name} - Логин
                
                </div>
                <form action="<?=$home?>/control/mail/templates?save" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <? foreach($templates as $item): ?>
                <div class="contbody_forms">
                    <b><?= $item['title'] ?></b><br/>
                    <input type="text" name="template[<?= $item['id'] ?>][subject]" placeholder="Тема письма">
                    <div class="texta">
                        <textarea rows="5" name="template[<?= $item['id'] ?>][template]"><?= $item['template'] ?></textarea>
                    </div>
                </div>
                <? endforeach; ?>
                <div class="contbody_forms">
                    <input type="submit" value="Сохранить" name="submit">
                </div>
                </form>
            </div>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/control/mail/mailing">Создать рассылку</a></div>
                <div class="elmenu"><a href="<?=$home?>/control/mail/arhive">Архив рассылок</a></div>
                <div class="elmenu"><a href="<?=$home?>/control/mail/templates">Шаблоны писем</a></div>
                <div class="elmenu"><a href="<?=$home?>/control/mail/settings">Настройка отправки</a></div>
                <div class="elmenu"><a href="<?=$home?>/control/mail/arhive">Отписавшиеся</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
