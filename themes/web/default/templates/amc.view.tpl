<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="fa fa-list-alt"></i> <?= $title ?></h2></div>
                <div class="contbody_forms">
                    <table class="table">
                        <tr>
                            <th>Арбитражный управляющий</th>
                            <th>Рейтинг</th>
                            <th>E-mail</th>
                            <th>Телефон</th>
                        </tr>
                        <? foreach($data as $item): ?>
                        <tr>
                            <td><a href="<?= core::$home ?>/amc/<?= $item['id'] ?>" target="blank"><?= $item['name'] ?><a/></td>
                            <td align="center" width="20">
                                <? if($item['rating'] > 5): ?>
                                    <? $class = 'class="plus"'; ?>
                                <? else: ?>
                                    <? $class = 'class="minus"'; ?>
                                <? endif; ?>
                                <a <?= $class ?> href="<?= core::$home ?>/amc/<?= $item['id'] ?>" target="blank"><?= $item['rating'] ?></a>
                            
                            </td>
                            <td align="center" width="150"><?= $item['email'] ?></td>
                            <td align="center" width="150"><?= $item['phone'] ?></td>
                        </tr>
                        <? endforeach; ?>
                    </table>
                    <?= $pagination ?>
                </div>
            </div>
        </td>
    </tr>
</table>

<style>
.fa-list-alt {
    color:#838488;
}


.table {
    
}

.table th {
    border:1px solid #eee;
    padding:8px;
}

.table td{
    border:1px solid #eee;
    padding:5px 10px;
}
.table tr:hover {
    background:#eee;
}
</style>