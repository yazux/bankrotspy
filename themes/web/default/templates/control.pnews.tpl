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

<form name="mess" action="<?=$home?>/control/pnews" method="post">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> Настройка новостей площадок</h2>
    </div>
    <div class="contbody_forms">
        <b>Доходность при которой отображать новости:</b><br/>
        <input type="text" name="baze_yield" value="<?=$baze_yield?>" />
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