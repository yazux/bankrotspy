<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Шаблоны писем</h2>
                </div>
                <div class="contbody_forms">
                <form action="<?=$home?>/control/mail/templates?save" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <? foreach($templates as $item): ?>
                <div class="contbody_forms">
                    <b><?= $item['title'] ?></b><br/>
                    <input type="text" name="template[<?= $item['id'] ?>][subject]" value="<?= $item['subject'] ?>" placeholder="Тема письма">
                    <div><label>Доступные теги:</label> <?= $item['tags'] ?></div>
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
            </div>
        </td>
        <? temp::include('control/mail/menu.tpl') ?>
    </tr>
</table>
