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

            <form name="mess" action="<?=$home?>/pages/add?att=<?=$att?>" method="post" enctype="multipart/form-data">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead"><h2><i class="icon-docs"></i> Новая страница</h2></div>
                    <div class="contbody_forms">
                        <b><?=lang('inst_name')?></b><br/>
                        <input type="text" name="art_name" value="<?=$name?>" />
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('keywords')?></b><br/>
                        <input type="text" name="keywords" value="<?=$keywords?>" />
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('description')?></b><br/>
                        <input type="text" name="description" value="<?=$description?>" />
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('data_opis')?></b><br/>
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="art_text" rows="15"><?=$text?></textarea></div>
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('pr_file')?></b><br/>
                        <input type="file" name="file" />
                        <input type="submit" name="add_attachment" value="<?=lang('do_file')?>"/>
                        <hr/>
                        <?if($att_true):?>
                        <br/>
                        <b><?=lang('pr_files_n')?></b><hr/>
                        <?foreach($out as $data):?>
                        <i class="icon-doc-inv"></i> <input type="text" value="[<?=$data['type']?>=<?=$data['filename']?>]<?=$data['name']?>[/<?=$data['type']?>]"/> <b><a href="<?=$home?>/article/file<?=$data['id']?>/<?=$data['nameraw']?>"><?=$data['name']?></a></b>

                        <input type="submit" name="del_attachment[<?=$data['id']?>]" value="<?=lang('del_th')?>"/>
                        <hr/>
                        <?endforeach?>
                        <?endif?>
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="<?=lang('save')?>" />
                    </div>
                </div>

            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>/control/pages">Отмена</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
