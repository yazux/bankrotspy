<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="fa fa-list-alt"></i> <?= $title ?></h2></div>
                <? if(!empty($textData)): ?>
                <div class="contbody_forms">
                    <?= $textData ?>
                </div>
                <? endif; ?>
                <div class="contbody_forms cf_double_table">
                    <div class="double_table">
                        <h2 class="table_header">Топ лотов за месяц</h2>
                        <table class="table">
                            <tr>
                                <th>№</th>
                                <th>Лот</th>
                                <th>Тип</th>
                                <th>Регион</th>
                                <th>Статус</th>
                                <th>Начальная цена</th>
                                <th>Просмотры</th>
                            </tr>
                            <? foreach($data_m as $item): ?>
                            <tr>
                                <td align="center" width="40"><?= $item['num'] ?></td>
                                <td width="255"><a class="namelink" href="/card/<?= $item['id'] ?>" target="_blank"><?= $item['name'] ?></a></td>
                                <td align="center" width="120">
                                    <?= $item['type'] ?>
                                </td>
                                <td align="center" width="120">
                                    <?= $item['place'] ?>
                                </td>
                                <td align="center" width="120">
                                <?= $item['status'] ?>
                                </td>
                                <td align="center" width="120">
                                    <?= $item['price'] ?>
                                </td>
                                <td align="center" width="125">
                                    <?= $item['views'] ?>
                                </td>
                            </tr>
                            <? endforeach; ?>
                        </table>
                    </div>
                    <div class="vertical_divider"></div>
                    <div class="double_table">
                        <h2 class="table_header">Топ лотов за все время</h2>
                        <table class="table">
                            <tr>
                                <th>№</th>
                                <th>Лот</th>
                                <th>Тип</th>
                                <th>Регион</th>
                                <th>Статус</th>
                                <th>Начальная цена</th>
                                <th>Просмотры</th>
                            </tr>
                            <? foreach($data_a as $item): ?>
                            <tr>
                                <td align="center" width="40"><?= $item['num'] ?></td>
                                <td width="255"><a class="namelink" href="/card/<?= $item['id'] ?>" target="_blank"><?= $item['name'] ?></a></td>
                                <td align="center" width="120">
                                    <?= $item['type'] ?>
                                </td>
                                <td align="center" width="120">
                                    <?= $item['place'] ?>
                                </td>
                                <td align="center" width="120">
                                <?= $item['status'] ?>
                                </td>
                                <td align="center" width="120">
                                    <?= $item['price'] ?>
                                </td>
                                <td align="center" width="125">
                                    <?= $item['views'] ?>
                                </td>
                            </tr>
                            <? endforeach; ?>
                        </table>
                    </div>
                </div>




            </div>
        </td>
    </tr>
</table>

<style>
.fa-list-alt {
    color:#838488;
}

.table th {
    background-color: #ebebeb;
    border: 1px solid #d1d1d1;
    color: #676767;
    font-size: 13px;
    font-weight: normal;
    padding: 4px 4px 4px 4px;
}

.table td{
    border:1px solid #e1e1e1;
    padding:5px 10px;
    font-size: 12px;
}
.table tr:hover {
    background:#f8f8f8;
}

h2.table_header{
    text-align: center;
    margin: 0;
    padding: 5px;
    background: #43464b;
/*    border-top: 1px solid #d1d1d1;
    border-left: 1px solid #d1d1d1;
    border-right: 1px solid #d1d1d1;*/
    color: #fff;
}

.double_table{
    float: left;
    width: 49%;
}

.vertical_divider{
    float: left;
    width: 2%;
    height: 10px;
}

.cf_double_table{
    overflow:auto;
}

</style>