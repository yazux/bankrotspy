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
        <center><?=lang('agree_text')?></center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/articles/acceptart?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="agree" type="submit" value="<?=lang('agree_act')?>" />  <a class="urlbutton" href="<?=$home?>/articles/post<?=$id?>"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>