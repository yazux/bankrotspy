<table>
    <tr>
        <td valign="top">

<form name="mess" action="<?=$home?>/control/tariffs" method="post">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> Настройки системы оплаты (ПСКБ)</h2>
    </div>
    <div class="contbody_forms">
        <b>Ключ системы оплаты:</b><br/>
        <input style="width: 460px" type="text" name="merchant_key" value="<?=$merchant_key?>" />
    </div>
    <div class="contbody_forms">
        <b>ID магазина:</b><br/>
        <input type="text" name="market_id" value="<?=$market_id?>" />
    </div>
    <div class="contbody_forms">
        <b>Префикс:</b> (4 буквы!)<br/>
        <input type="text" name="prefix" value="<?=$market_prefix?>" />
    </div>
    <div class="contbody_forms">
        <?=func::tagspanel('messarea');?>
        <div class="texta"><textarea id="messarea" name="mess" rows="4"><?=$text?></textarea></div>
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