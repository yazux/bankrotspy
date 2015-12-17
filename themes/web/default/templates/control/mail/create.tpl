<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Создание рассылки</h2>
                </div>
                <div class="contbody_forms">
                               
                </div>
                <form name="mess" action="<?=$home?>/control/mail/templates?save" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
              
                <div class="contbody_forms">
                    <b>Тема письма</b><br/>
                    <input type="text" name="template[<?= $item['id'] ?>][subject]" placeholder="Тема письма">
                </div>

                <div class="contbody_forms">
                        <b>Текст письма</b><br/>
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="art_text" rows="15"><?=$text?></textarea></div>
                </div>
                
                <div class="contbody_forms">
                        <b>Файлы</b><br/>
                        <input type="file" name="file" />
                        <input type="submit" name="add_attachment" value="Добавить"/>
                        <hr/>
                        <?if($att_true):?>
                        <br/>
                        <b><?=lang('pr_files_n')?></b><hr/>
                        <?foreach($out as $data):?>
                        <i class="icon-doc-inv"></i> <input type="text" value="[<?=$data['type']?>=<?=$data['filename']?>]<?=$data['name']?>[/<?=$data['type']?>]"/> <b><a href="<?=$home?>/article/file<?=$data['id']?>/<?=$data['nameraw']?>"><?=$data['name']?></a></b>

                        <input type="submit" name="del_attachment[<?=$data['id']?>]" value="Удалить"/>
                        <hr/>
                        <?endforeach?>
                        <?endif?>
                </div>
                
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
