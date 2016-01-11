<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Создание рассылки</h2>
                </div>
                <div class="contbody_forms">
                               
                </div>
                <form name="mess" action="<?=$home?>/control/mail/mailing" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input type="hidden" name="id" value="<?= $mail['id'] ?>" />
                <div class="contbody_forms">
                    <b>Группы подписчиков</b><br/>
                    <label>
                        <input type="checkbox" name="groups[-1]" checked="checked">
                        Все
                    </label>
                    <label>
                        <input type="checkbox" name="groups[0]">
                        Пользователь
                    </label>
                    <label>
                        <input type="checkbox" name="groups[10]">
                        Клиент
                    </label>
                    <label>
                        <input type="checkbox" name="groups[11]">
                        VIP-клиент
                    </label>
                </div>
                <div class="contbody_forms">
                    <b>Тема письма</b><br/>
                    <input type="text" name="subject" placeholder="Тема письма" value="<?= $mail['subject'] ?>">
                </div>
                <div class="contbody_forms">
                        <b>Текст письма</b><br/>
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="text_source" rows="15"><?= $mail['text_source'] ?></textarea></div>
                        <hr/>
                        <b>Изображения</b><br/><br/>
                        <a class="button upload">
                            Добавить файл
                        <input type="file" name="image" />
                        </a>
                        
                        <div class="images">
                            <? if(!empty($images)): ?>
                            <? foreach($images as $image): ?>
                            <hr/>
                            <div>
                                <span class="field" contenteditable="true">[img=<?= $image['name'] ?>]<?= $image['name'] ?>[/img]</span>
                                    <input type="hidden" name="images[]" value="<?= $image['name'] ?>"/>&nbsp;&nbsp;
                                    <b><?= $image['name'] ?></b>&nbsp;&nbsp;
                                    <a id="delete" class="button">Удалить</a>
                                </div>
                            <? endforeach; ?>
                            <? endif; ?>
                        </div>
                </div>
                
                <div class="contbody_forms">
                    <b>Вложения</b><br/><br/>
                    <a class="button upload">
                        Добавить файл
                        <input type="file" name="file" />
                    </a>
                        
                    <div class="attachments">
                        <? if(!empty($attachments)): ?>
                        <? foreach($attachments as $file): ?>
                        <hr/>
                        <div>
                            <input type="hidden" name="files[]" value="<?= $file['name'] ?>"/>
                            <b><?= $file['name'] ?></b>&nbsp;&nbsp;
                            <a id="delete" class="button">Удалить</a>
                        </div>
                        <? endforeach; ?>
                        <? endif; ?>
                    </div>
                </div>
                
                <div class="contbody_forms">
                    <input type="submit" value="Сохранить" name="submit">
                </div>
                </form>
            </div>
        </td>
        <? temp::include('control/mail/menu.tpl') ?>
    </tr>
</table>
<script>
$(function(){
    $('input[type=file]').on('change', function(){
        // тип файла изображение или вложение
        var fileType = $(this)[0].name;
        // поле с файлом
        var fileName = $(this)[0].files[0];
               
        var form = new FormData();
        form.append(fileType, fileName);
        
        $.ajax({
            url : '/control/mail/files?action=upload&type='+fileType+'&id=<?= $mail['id'] ?>',
            type : 'POST',
            dataType: 'json',
            data : form,
            processData: false,
            contentType: false,
            success : function(data) {
                if (fileType == 'image') {
                    var template = '<hr/><div><span class="field" contenteditable="true">[img='+data.name+']'+data.name+'[/img]</span><input type="hidden" name="images[]" value="'+data.name+'"/>&nbsp;&nbsp;<b>'+data.name+'</b>&nbsp;&nbsp;<a id="delete" class="button">Удалить</a></div>';
                    $('.images').append(template);
                } else {
                    var template = '<hr/><div><input type="hidden" name="files[]" value="'+data.name+'"/><b>'+data.name+'</b>&nbsp;&nbsp;<a id="delete" class="button">Удалить</a></div>';
                    $('.attachments').append(template);
                }
            }
        });
    });
    
    // удаление файлов
    $('.content').on('click', '#delete',  function(e){
        e.preventDefault();
        var div = $(this).parent();
        var file = $(this).parent().find('b').html();
            
        $.ajax({
            url : '/control/mail/files?action=delete&file=' + file + '&id=<?= $mail['id'] ?>',
            type : 'POST',
            dataType: 'json',
            success : function(data) {
                $(div).remove();
            }
        });
    });
});
</script>
<style>
.images div, .attachments div {
    padding:10px 0;
}
.field {
    background: #f9f8f8 url("../images/input_text.png") repeat-x scroll 0 0;
    border: 1px solid #a8a8a8;
    border-radius: 2px;
    display: inline-block;
    font-size: 14px;
    height: 18px;
    margin: 4px 0 5px;
    padding: 6px 9px 7px;
    vertical-align: middle;
    
}
.upload{
    display:block;
    position: relative;
    overflow: hidden;
    text-align:center;
    max-width:100px;
    margin-bottom:10px;
}

.upload input[type=file]{
    position: absolute;
    left: 0;
    top: 0;
    
    transform: scale(20);
    letter-spacing: 10em;     /* IE 9 fix */
    -ms-transform: scale(20); /* IE 9 fix */
    opacity: 0;
    cursor: pointer
}
</style>