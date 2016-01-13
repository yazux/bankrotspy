<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> Отписка от рассылки</h2></div>
                <div class="conthead">
                    <form method="POST" action="/unsubscribe?id=<?= $id ?>">
                        <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                        <label>Отписать <?= $email ?> от рассылки</label><br/>
                        <input type="submit" value="Отписаться от рассылки">
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>