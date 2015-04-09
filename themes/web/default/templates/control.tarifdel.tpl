
<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <center><b><?=lang('del_item')?></b></center>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center><?=lang('del_text')?> "<?=$name?>"?</center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/control/tarifdel?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="submit" type="submit" value="<?=lang('delete_act')?>" />  <a class="urlbutton" href="<?=$home?>/control/tset"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>