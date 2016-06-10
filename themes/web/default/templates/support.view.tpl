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
                    <h2>Запрос в техподдержку #<?=$id?></h2>
                </div>
                <div class="contbody_forms">
                    <table style="margin-left:-6px; margin-right:-6px;">
                        <tr>
                            <td valign="top" width="50px">
                                <?if ($avatar):?>
                                    <a href="<?=$home?>/user/profile?id=<?=$userid?>"><img class="avatar" src="<?=$home?><?=$avatar?>"/></a>
                                <?else:?>
                                    <a href="<?=$home?>/user/profile?id=<?=$userid?>"><img class="avatar" src="<?=$themepath?>/images/user.png"/></a>
                                <?endif?>
                            </td>
                            <td>
                                <table style="margin-bottom:2px">
                                    <tr>
                                        <td width="100%">
                                            <a href="<?=$home?>/user/profile?id=<?=$userid?>"><b><?=$login?></b></a>
                                            <?if($us_tech_online):?><span class="us_on"> <?=lang('lang_on')?></span><?else:?><span class="us_off"> <?=lang('lang_off')?></span><?endif?><br/>
                                        </td>
                                        <td>
                                            <span class="time"><?=$time?></span>
                                        </td>
                                    </tr>
                                </table>
                                <div class="commtext"><?=$text?></div>
                            </td>
                        </tr>
                    </table>
                </div>

                <?foreach($com as $com): ?>
                    <? temp::include('comms.class.show.tpl') ?>
                <?endforeach?>

                <div class="contfin_forms">
                    <br/>
                </div>
            </div>

            <div class="content">
                <?if(!$tech_close):?>
                <div class="conthead">
                    <b>Добавить ответ:</b>
                </div>
                <form name="mess" action="<?=$home?>/support/view?id=<?=$id?>" method="post">
                    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                    <div class="contbody_forms">
                        <input type="hidden" name="process" value="1">
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="msg" rows="5"></textarea></div>
                    </div>
                    <!--div class="contbody_forms"><i class="icon-attention"></i> Если Вам нужно прикрепить файл (скриншот), тогда воспользуйтесь бесплатными сервисами, например:
                        <a target="_blank" href="http://pixs.ru/">http://pixs.ru/</a> или
                        <a target="_blank" href=""https://gyazo.com/ru>https://gyazo.com/ru</a> и вставьте ссылку на изображение в сообщение.
                    </div-->
                    <div class="contbody_forms">
                        <div id="attach">Прикрепить фото</div> <span id="status" ></span>
                        <div id="uploadfile"></div>
                        <div id="files"></div>
                    </div>
                    <div class="contfintext">
                        <input type="hidden" name="id" value="<?=$id?>" id="postId" />
                        <input type="submit" name="submit" size="14" value="Отправить" />
                    </div>
                </form>
                <?else:?>
                <div class="contbody_forms">Обращение закрыто.</div>
                <?endif?>
            </div>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Техподдержка</div>
                <?if(!$tech_close):?>
                <div class="elmenu"><a href="<?=$home?>/support/close?id=<?=$id?>">Закрыть обращение</a></div>
                <?endif?>
                <div class="elmenu"><a href="<?=$home?>/support">Вернуться</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>

<script type="text/javascript" src="/themes/web/default/js/ajaxupload.js" ></script>

<script>
    $(document).ready(function(){

        var btnUpload=$('#attach');
        var status=$('#status');
        var postId = $('#postId').val();
        var a = new AjaxUpload(btnUpload, {
            action : '<?=$home?>/support/fileupload?id='+postId,
            name : 'uploadfile',
            onSubmit: function(file, ext){
                //alert(data.id);
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    // check for valid file extension
                    status.text('Only JPG, PNG or GIF files are allowed');
                    return false;
                }
                status.text('Uploading...');
            },
            onComplete: function(file, response){
                var res = JSON.parse(response);
                status.text('');

                if( res.error == false ){
                    $('<div class="file" val="'+res.file+'"></div>').appendTo('#files').html('<img width="150" src="/data/att_support/'+res.file+'" alt="" /><br />'+res.file);
                } else{
                    $('<div></div>').appendTo('#files').text(file).addClass('error');
                }
                
                $('.file').click(function(){
                    var name = $(this).attr('val');
                    insertTextAtCursor(document.getElementById('messarea'),' [img='+name+']'+name+'[/img] ');
                });
            }
        });
        
        function insertTextAtCursor(el, text, offset) {
            var val = el.value, endIndex, range, doc = el.ownerDocument;
            if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
                endIndex = el.selectionEnd;
                el.value = val.slice(0, endIndex) + text + val.slice(endIndex);
                el.selectionStart = el.selectionEnd = endIndex + text.length+(offset?offset:0);
            } else if (doc.selection != "undefined" && doc.selection.createRange) {
                el.focus();
                range = doc.selection.createRange();
                range.collapse(false);
                range.text = text;
                range.select();
            }
        }
    });
</script>

<style type="text/css">
#attach{
    cursor: pointer;
    background: #feab2e;
    box-shadow: none;
    border-right: rgba(253, 255, 253, 0.49) 2px solid;
    border-bottom: rgba(253, 255, 253, 0.49) 2px solid;
    text-decoration: none;
    padding: 10px;
    border-radius: 6px;
    color: white;
    font-weight: bold;
    font-size: 13px;
    width:115px;
}

#previews{
    height: 42px;
    line-height: 45px;
    padding-left: 20px;
    color: #000;
    overflow: hidden;
}

#attachInputId{
    display: none;
}

.file{
    //float: left;
    width: 150px;
    margin: 5px;
    padding: 5px;
    border: 1px #C7C7C7 solid;
    text-align: center;
}

</style>