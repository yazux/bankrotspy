<div class="content">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b><?=lang('art_decline')?></b><br/>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center><?=lang('decline_text')?></center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/articles/declineart?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="decline" type="submit" value="<?=lang('decline_act')?>" />  <a class="urlbutton" href="<?=$home?>/articles/post<?=$id?>"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>