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
                <div class="contbody_forms">
                    <? if ($access == 1): ?>
                    <form style="display:block;margin-bottom:20px;">
                        <input type="text" name="search" value="<?=$search?>" placeholder="Имя или название">&nbsp;
                        <input type="submit" class="urlbutton_index button_no_top_index" value="Найти">
                        <a class="urlbutton_index button_no_top_index" href="/winners/">Очистить</a>
                    </form>
                    <? endif; ?>
                    
                    <div class="results">
                        Показаны результаты: <?= $start ?>-<?= $end ?> из <?= $total ?>
                    </div>
                    <table class="table">
                        <tr>
                            <th>
                                Имя
                                <?if($sortField=='fio'):?>
                                    <?if($sortOrder == 'DESC'):?>
                                        <a href="?sortField=fio&sortOrder=ASC">
                                            <img src="/themes/web/default/images/table/desc.png">
                                        </a>
                                    <?else:?>
                                        <a href="?sortField=fio&sortOrder=DESC">
                                            <img src="/themes/web/default/images/table/asc.png">
                                        </a>
                                    <?endif;?>
                                <?else:?>
                                    <a href="?sortField=fio&sortOrder=ASC">
                                        <img src="/themes/web/default/images/table/bg.png">
                                    </a>
                                <?endif;?>
                            </th>
                            <th>
                                Кол-во выйгранных лотов
                                <?if($sortField=='kolvo'):?>
                                    <?if($sortOrder == 'DESC'):?>
                                        <a href="?sortField=kolvo&sortOrder=ASC">
                                            <img src="/themes/web/default/images/table/desc.png">
                                        </a>
                                    <?else:?>
                                        <a href="?sortField=kolvo&sortOrder=DESC">
                                            <img src="/themes/web/default/images/table/asc.png">
                                        </a>
                                    <?endif;?>
                                <?else:?>
                                    <a href="?sortField=kolvo&sortOrder=ASC">
                                        <img src="/themes/web/default/images/table/bg.png">
                                    </a>
                                <?endif;?>
                            </th>
                            <th>Потраченная сумма (руб.)
                                 <?if($sortField=='suma'):?>
                                    <?if($sortOrder == 'DESC'):?>
                                        <a href="?sortField=suma&sortOrder=ASC">
                                            <img src="/themes/web/default/images/table/desc.png">
                                        </a>
                                    <?else:?>
                                        <a href="?sortField=suma&sortOrder=DESC">
                                            <img src="/themes/web/default/images/table/asc.png">
                                        </a>
                                    <?endif;?>
                                <?else:?>
                                    <a href="?sortField=suma&sortOrder=ASC">
                                        <img src="/themes/web/default/images/table/bg.png">
                                    </a>
                                <?endif;?>
                            </th>
                        </tr>
                        <? foreach($data as $item): ?>
                        <tr>
                            <td width="255"><?= $item['fio'] ?></td>
                            <td align="center" width="120">
                                <?= $item['kolvo'] ?>
                            </td>
                            <td align="center" width="120">
                                <?= $item['suma'] ?>
                            </td>
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