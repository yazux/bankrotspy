<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Ошибки разбора API</h2>
                </div>
                <?if($rmenu):?>
                <div class="contbody_forms">
                    <b>Всего:</b> <?=$total?>
                </div>


            <?foreach($rmenu as $rmenu): ?>
            <div class="contbody_forms">
                <table>
                    <tr>
                        <td valign="top" style="width: 30px;padding: 4px 0 4px 0"><i class="icon-attention"></i></td>
                        <td>
                            Лот: <b><?=$rmenu['name']?></b><br/>
                            <hr style="margin: 3px 6px 3px 8px;border-color: white;"/>
                            <span style="font-size: 14px;color: red;">Ошибки разбора: <?=$rmenu['undtext_err']?></span>
                            <hr style="margin: 3px 6px 3px 8px"/>
                            <span style="font-size: 13px;"><span style="color: #949498">Исходные данные:</span> <?=$rmenu['undtext']?></span>
                        </td>
                    </tr>
                </table>

            </div>
            <?endforeach?>

            <?else:?>
            <div class="contbody_forms">Нет лотов с ошибками</div>
            <?endif?>

                <div class="contfin_forms">
                    <a class="urlbutton" href="<?=$home?>/parserstat/renewlots">Перезалить лоты</a> <a class="urlbutton" href="<?=$home?>/parserstat/clearlots">Очистить таблицу</a>
                </div>
            </div>


            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
        </td>
        <td class="right_back_menu">
            <? temp::include('parsersstat.index.right.tpl') ?>
        </td>
    </tr>
</table>