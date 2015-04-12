<table>
    <tr>
        <td valign="top">

<form name="mess" action="<?=$home?>/control" method="post">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> <?=lang('base_settings')?></h2>
    </div>
    <div class="contbody_forms">
        <b><?=lang('site_domain')?></b><br/>
        <input type="text" name="site_domain" value="<?=$site_domain?>" />
    </div>
    <div class="contbody_forms">
        <b><?=lang('site_name')?></b><br/>
        <input type="text" name="site_name" value="<?=$site_name?>" />
    </div>
    <div class="contbody_forms">
        <b><?=lang('site_keywords')?></b><br/>
        <div class="texta"><textarea name="site_keywords" rows="5"><?=$site_keywords?></textarea></div>
    </div>
    <div class="contbody_forms">
        <b><?=lang('site_description')?></b><br/>
        <div class="texta"><textarea name="site_description" rows="5"><?=$site_description?></textarea></div>
    </div>
    <div class="contbody_forms">
        <b>Текст на странице регистрации:</b><br/>
        <?=func::tagspanel('messarea');?>
        <div class="texta"><textarea id="messarea" name="mess" rows="5"><?=$text?></textarea></div>
    </div>
    <div class="contfin_forms">
        <input name="submit" type="submit" value="<?=lang('save')?>" />
    </div>
</div>
</form>


        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>