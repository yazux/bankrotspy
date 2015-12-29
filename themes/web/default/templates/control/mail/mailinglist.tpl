<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Список рассылок</h2>
                </div>
                <div class="contbody_forms">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Тема письма</th>
                            <th width="80">Время создания</th>
                            <th width="80">Статус</th>
                            <th width="100">Получатели: всего / отправлено</th>
                            <th width="80">Начало отправки</th>
                            <th width="80">Завершение отправки</th>
                            <th width="120">Управление</th>
                        </tr>
                    </thead>
                    <tbody>
                <? if(!empty($mailing)): ?>
                <? foreach($mailing as $mail): ?>
                    <tr>
                        <td>
                            <?= $mail['id'] ?>
                        </td>
                        <td>
                            <?= $mail['subject'] ?>
                        </td>
                        <td>
                            <?= $mail['created'] ?>
                        </td>
                        <td>
                            <?= $mail['status'] ?>
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            <?= $mail['start'] ?>
                        </td>
                        <td>
                            <?= $mail['end'] ?>
                        </td>
                        <td>
                            <a href="/control/mail/mailing?action=delete&id=<?= $mail['id'] ?>" class="btn btn-delete"><i class="fa fa-trash"></i></a>
                            <a href="/control/mail/mailing?action=edit&id=<?= $mail['id'] ?>" class="btn btn-edit"><i class="fa fa-pencil"></i></a>
                            <? if($mail['status_act'] !== '1'): ?>
                            <a href="/control/mail/mailing?action=start&id=<?= $mail['id'] ?>" class="btn btn-play"><i class="fa fa-play"></i></a> 
                            <? else: ?>
                            <a href="/control/mail/mailing?action=stop&id=<?= $mail['id'] ?>" class="btn btn-play"><i class="fa fa-pause"></i></a>                             
                            <? endif; ?>
                        </td>
                    </tr>
                <? endforeach; ?>
                <? else: ?>
                    <tr><td colspan="8">Нет рассылок</td></tr>
                <? endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </td>
        <? temp::include('control/mail/menu.tpl') ?>
    </tr>
</table>
<style>
.table {
    border-collapse:collapse;
}

.table td, .table th {
    border:1px solid #ccc;
    text-align:center;
    padding:10px;
}
.table th {
    background:#ebebeb;   
}
.table tbody tr:hover {
    background:#f9f8f8;
}

.btn {
    padding:4px 8px;
    border-radius:3px;
    margin:3px;
}

.btn:hover {
    color:#fff;
}

.btn-play {
    background-color: #337ab7;
    border-color: #2e6da4;
    color: #fff;
}

.btn-play:hover {
    background-color: #286090;
    border-color: #204d74;
}

.btn-delete {
    background-color: #d9534f;
    border-color: #d43f3a;
    color: #fff;
}
.btn-delete:hover {
    background-color: #c9302c;
    border-color: #ac2925;
}

.btn-edit {
    background-color: #f0ad4e;
    border-color: #eea236;
    color: #fff;
}

.btn-edit:hover {
    background-color: #ec971f;
    border-color: #d58512;
}
</style>