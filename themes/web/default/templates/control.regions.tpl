<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Регионы</h2>
                </div>

                <?if($addRegions):?>
                    <?foreach($addRegions as $addRegion): ?>
                    <div class="contbody_forms">
                        <table>
                            <tr>
                                <td style="width: 500px; padding: 4px 0 4px 0"><b><?=$addRegion['name']?></b> (id: <?=$addRegion['id']?>)</td>
                                <td>
                                    <form action="<?=$home?>/control/regions" method="get">
                                        <input type="hidden" value="<?=$addRegion['id']?>" name="addRegion">
                                        <select name="region">
                                            <option value="0">-- Не выбрано --</option>
                                            <option value="-1">-- Неопределенный регион --</option>
                                            <?foreach($regions as $region): ?>
                                                <option value="<?=$region['id']?>"><?=$region['name']?></option>
                                            <?endforeach?>
                                        </select>
                                        <input type="submit" value="Назначить">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?endforeach?>
                <?else:?>
                    <div class="contbody_forms">Нет ни одного региона.</div>
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