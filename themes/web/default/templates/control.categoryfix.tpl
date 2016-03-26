<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Присвоение категорий</h2>
                </div>

            <?if( $justLeft > 0 ):?>
                <p style="margin: 20px;">
                    Отсортировано <?=$limit?> лотов. 
                    Еще осталось пересортировать <?=$justLeft?> лотов.
                </p>
            <?endif?>
            
            <?if($lots):?>
                <table cellspacing="5" cellpadding="5" border="1" style="border-collapse: collapse;">
                    <tr>
                        <th>ID</th>
                        <th>Лот</th>
                        <th>Текущая категория</th>
                        <th>Назначена</th>
                        <th>Ключевики</th>
                        <th>Стоп слова</th>
                    </tr>

                    <?foreach($lots as $lot): ?>
                        <tr>
                            <td><a href="/card/<?=$lot['id']?>" title="Просмотреть лот"><?=$lot['id']?></a></td>
                            <td><?=$lot['text']?></td>
                            <td><?=$allCategories[$lot['currentCategory']]?></td>
                            <td><?=$allCategories[$lot['categoryId']]?></td>
                            <td><?=$lot['include']?></td>
                            <td><?=$lot['exclude']?></td>
                        </tr>
                    <?endforeach?>
                </table>
            <?else:?>
                <div class="contbody_forms">Нет ни одного лота, который бы нуждался в присвоении категории.</div>
            <?endif?>

                <div class="contfin_forms">
                  <br/>
                </div>
            </div>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.categories.right.tpl') ?>
        </td>
    </tr>
</table>