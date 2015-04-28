
<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <center><b>Очистка лотов с ошибками</b></center>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center>Вы действительно хотите удалить все лоты с ошибками? Действие нельзя будет отменить!</center>
    </div>
    <div class="contfintext">
        <form name="mess" action="<?=$home?>/parserstat/clearlots" method="post">
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <center><input name="submit" type="submit" value="Удалить" />  <a class="urlbutton" href="<?=$home?>/parserstat/errlots">Отмена</a></center>
        </form>
    </div>
</div>