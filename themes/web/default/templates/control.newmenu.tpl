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

<form name="mess" action="<?=$home?>/control/newmenu" method="post">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> Новый пункт меню</h2>
    </div>
    <div class="contbody_forms">
        <b><?=lang('link_name')?></b><br/>
        <input name="link_name" type="text" value="<?=$name?>"/>
    </div>
    <div class="contbody_forms">
        <b><?=lang('pruf_link')?></b><br/>
        <input name="pruf_link" type="text" value="<?=$link?>"/>
    </div>
    <?if($other_sections):?>
    <div class="contbody_forms">
        <b><?=lang('link_sort')?></b><br/>
        <select name="link_sort">
            <?foreach($outs as $outs): ?>
            <option value="<?=$outs['id']?>"><?=$outs['name']?></option>
            <?endforeach?>
            <option value="0">- <?=lang('in_beginning')?></option>
        </select>
    </div>
    <?endif?>
    <div class="contbody_forms">
        <b><?=lang('one_cnt')?></b><br/>
        <select name="one_cnt">
            <option value="">- <?=lang('no_count')?></option>
            <?foreach($cntall as $cnkey=>$cnval): ?>
            <option <?if($one_cnt == $cnkey):?>selected="selected"<?endif?> value="<?=$cnkey?>"><?=$cnkey?></option>
            <?endforeach?>
        </select>
    </div>
    <div class="contbody_forms">
        <b><?=lang('two_cnt')?></b><br/>
        <select name="two_cnt">
            <option value="">- <?=lang('no_count')?></option>
            <?foreach($cntall as $cnkey=>$cnval): ?>
            <option <?if($two_cnt == $cnkey):?>selected="selected"<?endif?> value="<?=$cnkey?>"><?=$cnkey?></option>
            <?endforeach?>
        </select>
    </div>
    <div class="contbody_forms">
        <b><?=lang('two_moder_cnt')?></b><br/>
        <select name="three_cnt">
            <option value="">- <?=lang('no_count')?></option>
            <?foreach($cntall as $cnkey=>$cnval): ?>
            <option <?if($three_cnt == $cnkey):?>selected="selected"<?endif?> value="<?=$cnkey?>"><?=$cnkey?></option>
            <?endforeach?>
        </select>
    </div>
    <div class="contfin_forms">
        <input name="submit" type="submit" value="<?=lang('add_form')?>" />
    </div>
</div>
</form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt"><?=lang('menu_settings')?>:</div>
                <div class="elmenu"><a href="<?=$home?>/control/menu">Вернуться</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>