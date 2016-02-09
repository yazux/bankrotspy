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
            <form name="mess" action="<?=$home?>/news/create" method="post" enctype="multipart/form-data">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> Добавить новость</h2></div>
                <div class="contbody_forms">
                    <b>Заголовок новости:</b><br/>
                    <input name="newshead" type="text" size="60" value="<?=$name?>"/><br/>
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
                <div class="contbody_forms">
                    <b>Загрузить файл:</b><br/>
                    <input type="file" name="file" />
                    <input type="submit" name="add_attachment" value="Загрузить"/>
                </div>
                
                <?if($att_true):?>
                <div class="contbody_forms">
                    <b>Файлы</b><br/>
                    <?foreach($out as $data):?>
                    <hr/>
                    <i class="icon-attach"></i>
                    <input type="text" value="[<?=$data['type']?>=<?=$data['filename']?>]<?=$data['name']?>[/<?=$data['type']?>]"/>
                    <input type="submit" name="del_attachment[<?=$data['id']?>]" value="Удалить"/>
                    <b><a target="_blank" href="<?=$home?>/load/file<?=$data['id']?>/<?=$data['nameraw']?>"><?=$data['name']?></a></b>           
                    <?endforeach?>
                </div>
                <?endif?>
                
                <div class="contfintext">
                    <input name="submit" type="submit" value="Добавить" />
                </div>
            </div>
            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>/news">Отмена</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>