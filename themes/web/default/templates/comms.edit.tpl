<form name="mess"
      action="<?=$home?>/comms/edit?mod=<?=$module?>&amp;act=<?=$action?>&amp;id=<?=$id?>&amp;mid=<?=$mid?><?if($page):?>&amp;page=<?=$page?><?endif?>"
      method="post" enctype="multipart/form-data">
    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>

    <?if ($error):?>
    <div class="error">
        <?foreach($error as $error): ?>
        <?=$error?><br/>
        <?endforeach?>
    </div>
    <?endif?>

    <?if($comm_prev):?>
    <div id="comms" class="content">
        <div class="conthead">
            <table>
                <tr>
                    <td>
                        <b><?=lang('comments_prev')?></b>
                    </td>
                </tr>
            </table>
        </div>

        <? temp::include('comms.class.show.tpl') ?>

        <div class="contfintext"></div>
    </div>
    <?endif?>

    <div class="content">
        <div class="conthead">
            <h2><?=lang('edit_comm_d')?></h2>
        </div>
        <div class="contbody_forms">
            <?=func::tagspanel('messarea');?>
            <div class="texta"><textarea id="messarea" name="post" rows="15"><?=$text?></textarea></div>
        </div>
        <div class="contfin_forms">
            <input name="submit" type="submit" value="<?=lang('save')?>"/> &nbsp;
            <?if($comm_prev):?>
            <input name="preview" class="button_noright" type="submit" value="<?=lang('preview')?>"/><input class="button_noleft" title="<?=lang('exitpreview')?>" name="exitpreview" type="submit" value="X"/>
            <?else:?>
            <input name="preview" type="submit" value="<?=lang('preview')?>"/>
            <?endif?>
            <a class="urlbutton"
               href="<?=$burl?>"><?=lang('cancel')?></a>
        </div>
    </div>

</form>