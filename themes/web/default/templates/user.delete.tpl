<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b><?=lang('del_user')?></b><br/>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center><?=lang('del_stat')?></center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/user/delete?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="delete" type="submit" value="<?=lang('delete')?>" />  <a class="urlbutton" href="<?=$home?>/user/profile?id=<?=$id?>"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>