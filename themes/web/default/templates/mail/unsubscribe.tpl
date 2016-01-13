<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> Отписка от рассылки</h2></div>
                <div class="conthead">
                    <form method="POST" action="/unsubscribe?id=<?= $id ?>">
                        <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                        <input type="submit" value="Отписаться">
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>