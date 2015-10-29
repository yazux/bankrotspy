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
                    
                    <form style="display:block;margin-bottom:20px;">
                        <input type="text" name="search" placeholder="Название, ИНН, E-mail, Телефон">&nbsp;
                        <input type="submit" class="urlbutton_index button_no_top_index" value="Найти">
                        <a class="urlbutton_index button_no_top_index" href="/amc/">Очистить</a>
                    </form>
                    
                    <div class="results">
                        <? if(!$search): ?>
                        Показаны результаты: <?= $start ?>-<?= $end ?> из <?= $total ?>
                        <? else: ?>
                        Всего найдено: <?= $search ?>
                        <? endif; ?>
                    </div>
                    <table class="table">
                        <tr>
                            <th>Арбитражный управляющий</th>
                            <th>Рейтинг</th>
                            <th>Документы суда</th>
                            <th>Документы ФАС</th>
                            <th>E-mail</th>
                            <th>Телефон</th>
                        </tr>
                        <? foreach($data as $item): ?>
                        <tr>
                            <td width="400"><a class="namelink" href="<?= core::$home ?>/amc/<?= $item['id'] ?>" target="_blank"><?= $item['name'] ?></a></td>
                            <td align="center" width="150">
                                <? if($item['totaldoc'] < 3 && $item['totaldoc'] > 0 ): ?>
                                    <? $rating = 'Мало данных'; ?>
                                <? elseif($item['totaldoc'] == 0): ?>
                                    <? $rating = 'Нет данных'; ?>
                                <? elseif($item['rating'] > 5): ?>
                                    <? 
                                        $rating = '<a class="plus" href="' . core::$home . '/amc/' . $item['id'] . '" target="_blank">' . $item['rating'] . '</a>';
                                    ?>
                                <? else: ?>
                                    <?
                                        $rating = '<a class="minus" href="' . core::$home . '/amc/' . $item['id'] . '" target="_blank">' . $item['rating'] . '</a>';
                                    ?>
                                <? endif; ?>
                                <?= $rating ?>
                            
                            </td>
                            <td align="center" width="150">
                            <? if(!empty($item['totaldoc'])): ?>
                                <a class="namelink" href="<?= $item['linkdocs'] ?>" target="_blank">Смотреть</a>
                            <? else: ?>
                                Нет данных
                            <? endif; ?>
                            </td>
                            <td align="center" width="150">
                             <? if(!empty($item['fasdocs'])): ?>
                                <a class="namelink" href="<?= $item['fasdocs'] ?>" target="_blank">Смотреть</a>
                            <? else: ?>
                                Нет данных
                            <? endif; ?>
                            </td>
                            <td align="center" width="150"><?= $item['email'] ?></td>
                            <td align="center" width="150"><?= !empty($item['phone']) ? $item['phone'] : ''; ?></td>
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