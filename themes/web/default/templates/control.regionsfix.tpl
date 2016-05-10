<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Статистика определения регионов</h2>
                </div>

                <?if($messages):?>
                    <?foreach($messages as $message): ?>
                    <div class="contbody_forms">
                        <table>
                            <tr>
                                <td>
                                    <?=$message?>
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