<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="fa fa-list-alt"></i> <?= $title ?></h2></div>
                <div class="contbody_forms">
                    <div class="contbody_forms_link"><a href="/amc">Арб. управляющие</a></div>
                    <div class="contbody_forms_link"><a href="/platforms">Торговые площадки</a></div>
                    <div class="contbody_forms_link"><a href="/debtors">Должники</a></div>
                </div>

                <? if(!empty($textData)): ?>
                <div class="contbody_forms">
                    <?= $textData ?>
                </div>
                <? endif; ?>
                <div class="contbody_forms">
                    <? if ($access == 1): ?>
                    <form style="display:block;margin-bottom:20px;">
                        <input type="text" name="search" value="<?=$search?>" placeholder="Название, ИНН, E-mail, Телефон">&nbsp;
                        <input type="submit" class="urlbutton_index button_no_top_index" value="Найти">
                        <a class="urlbutton_index button_no_top_index" href="/amc/">Очистить</a>
                    </form>
                    <? endif; ?>
                    
                    <div class="results">
                        Показаны результаты: <?= $start ?>-<?= $end ?> из <?= $total ?>
                    </div>
                    <table class="table">
                        <tr>
                            <th>
                                Арбитражный управляющий 
                                <?if($sortField=='name'):?>
                                    <?if($sortOrder == 'DESC'):?>
                                        <a href="?sortField=name&sortOrder=ASC">
                                            <img src="/themes/web/default/images/table/desc.png">
                                        </a>
                                    <?else:?>
                                        <a href="?sortField=name&sortOrder=DESC">
                                            <img src="/themes/web/default/images/table/asc.png">
                                        </a>
                                    <?endif;?>
                                <?else:?>
                                    <a href="?sortField=name&sortOrder=ASC">
                                        <img src="/themes/web/default/images/table/bg.png">
                                    </a>
                                <?endif;?>
                            </th>
                            <th>
                                Рейтинг
                                <?if($sortField=='bal'):?>
                                    <?if($sortOrder == 'DESC'):?>
                                        <a href="?sortField=bal&sortOrder=ASC">
                                            <img src="/themes/web/default/images/table/desc.png">
                                        </a>
                                    <?else:?>
                                        <a href="?sortField=bal&sortOrder=DESC">
                                            <img src="/themes/web/default/images/table/asc.png">
                                        </a>
                                    <?endif;?>
                                <?else:?>
                                    <a href="?sortField=bal&sortOrder=ASC">
                                        <img src="/themes/web/default/images/table/bg.png">
                                    </a>
                                <?endif;?>
                            </th>
                            <th>Кол-во лотов
                                 <?if($sortField=='cnt'):?>
                                    <?if($sortOrder == 'DESC'):?>
                                        <a href="?sortField=cnt&sortOrder=ASC">
                                            <img src="/themes/web/default/images/table/desc.png">
                                        </a>
                                    <?else:?>
                                        <a href="?sortField=cnt&sortOrder=DESC">
                                            <img src="/themes/web/default/images/table/asc.png">
                                        </a>
                                    <?endif;?>
                                <?else:?>
                                    <a href="?sortField=cnt&sortOrder=ASC">
                                        <img src="/themes/web/default/images/table/bg.png">
                                    </a>
                                <?endif;?>
                            </th>
                            <th>Документы судов</th>
                            <th>Документы ФАС</th>
                            <th>Федресурс</th>
                            <th>E-mail</th>
                            <th>Телефон</th>
                        </tr>
                        <? foreach($data as $item): ?>
                        <tr>
                            <td width="255"><a class="namelink" href="<?= core::$home ?>/amc/<?= $item['id'] ?>" target="_blank"><?= $item['name'] ?></a></td>
                            <td align="center" width="120">
                                <?= $item['rating'] ?>
                            </td>
                            <td align="center" width="120">
                                <?= $item['cnt'] ?>
                            </td>
                            <td align="center" width="120">
                            <?= $item['linkdocs'] ?>
                            </td>
                            <td align="center" width="120">
                                <?= $item['fasdocs'] ?>
                            </td>
                            <td align="center" width="120">
                                <?= $item['org_profile'] ?>
                            </td>
                            <td align="center" width="125"><?= $item['email'] ?></td>
                            <td align="center" width="125"><?= !empty($item['phone']) ? $item['phone'] : ''; ?></td>
                        </tr>
                        <? endforeach; ?>
                    </table>
                    <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
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
    font-size: 14px;
    font-weight: normal;
    padding: 4px 16px 4px 3px;
}

.table td{
    border:1px solid #e1e1e1;
    padding:5px 10px;
}
.table tr:hover {
    background:#f8f8f8;
}


</style>