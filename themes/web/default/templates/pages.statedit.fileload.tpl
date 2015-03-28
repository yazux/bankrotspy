<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b><?=lang('fileload')?></b><br/>
                </td>
            </tr>
        </table>
    </div>
    <form name="mess" action="<?=$link?>" method="POST">
        <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
    <div class="contbodytext">
        <center><?=$otext?></center>
    </div>
        <?if($savevar):?>
        <?foreach($savevar as $key=>$value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
        <?endforeach?>
        <?endif?>
    <div class="contfintext">
        <center><input type="submit" value="<?=lang('continue')?>" /></center>
    </div>
    </form>
</div>
