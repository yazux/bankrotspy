<div class="content">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b><?=lang('art_agree')?></b><br/>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center><?=lang('agree_text')?> <?if($onmoder_stat):?><?=lang('agree_text_onmoder')?><?endif?></center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/articles/public?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="agree" type="submit" value="<?=lang('agree_act')?>" />  <a class="urlbutton" href="<?=$home?>/articles/post<?=$id?>"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>