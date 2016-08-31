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
                    
                    <form style="display:block;margin-bottom:20px;">
                        <input type="text" name="search" value="<?=$search?>" placeholder="Название, Номер">&nbsp;
                        <input type="submit" class="urlbutton_index button_no_top_index" value="Найти">
                        <a class="urlbutton_index button_no_top_index" href="/amc/">Очистить</a>
                    </form>
                    
                    
                    <div class="results">
                        Показаны результаты: <?= $start ?>-<?= $end ?> из <?= $total ?>
                    </div>
                    <table class="table">
                        <tr>
                            <th>
                                №             
                            </th>
                            <th>Ресурс</th>
                        </tr>
                        <? foreach($data as $item): ?>
                        <tr>
                            <td align="center" width="150"><?= $item['id'] ?></td>
                            <td align="center" width="1000"><a class="namelink" href="<?=  'http://'.$item['platform_url'] ?>" target="_blank"><?= $item['platform_url'] ?></a></td>
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