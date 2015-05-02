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
        <center>Вы действительно хотите удалить поисковой профиль?</center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/user/deleteprofile?id=<?=$id?>" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="submit" type="submit" value="Удалить" />  <a class="urlbutton" href="<?=$home?>/user/cab">Отмена</a></center>
        </form>
    </div>
</div>