<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b>Удаление страницы</b><br/>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center>Вы действительно хотите удалить страницу, все прикрепленные файлы будут также удалены!</center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/pages/statdel?id=<?=$id?>"  method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="delete" type="submit" value="Удалить" />  <a class="urlbutton" href="<?=$home?>/pages/<?=$id?>"><?=lang('cancel')?></a></center>
        </form>
    </div>
</div>