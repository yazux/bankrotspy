<table>
    <tr>
        <td valign="top">

<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> Настройки тарифов</h2>
    </div>

    <?if($rmenu):?>

    <?foreach($rmenu as $rmenu): ?>
    <div class="contbody_forms">
        <table>
            <tr>
              <td valign="top" style="width: 30px;padding: 4px 0 4px 0"><i class="icon-list"></i></td>
              <td>
                  <b><?=$rmenu['name']?></b> (<?=$rmenu['price']?> руб.)<br/>
                  <hr style="margin: 3px 6px 3px 8px"/>
                  Доступ: <?=$rmenu['longtime']?><br/>
                  Период: <?=$rmenu['typetime']?><br/>
                  <span style="font-size: 13px;color: #b1aea8;"><?=$rmenu['subtext']?></span>
              </td>
                <td class="cont_act"><a title="Редактировать" href="<?=$home?>/control/tarifedit?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                <td class="cont_act"><a title="Удалить" href="<?=$home?>/control/tarifdel?id=<?=$rmenu['id']?>"><i class="icon-delete"></i></a></td>
            </tr>
        </table>
    </div>
    <?endforeach?>

    <?else:?>
        <div class="contbody_forms">Нет ни одного тарифа.</div>
    <?endif?>

    <div class="contfin">
        <a class="urlbutton" href="<?=$home?>/control/newtarif">Новый тариф</a>
    </div>
</div>


            <form name="mess" action="<?=$home?>/control/tset" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div style="margin-top: 29px;" class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i> Настройки системы оплаты (ПСКБ)</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>Ключ системы оплаты:</b><br/>
                        <input style="width: 460px" type="text" name="merchant_key" value="<?=$merchant_key?>" />
                    </div>
                    <div class="contbody_forms">
                        <b>ID магазина:</b><br/>
                        <input type="text" name="market_id" value="<?=$market_id?>" />
                    </div>
                    <div class="contbody_forms">
                        <b>Префикс:</b> (4 буквы!)<br/>
                        <input type="text" name="prefix" value="<?=$market_prefix?>" />
                    </div>
                    <div class="contbody_forms">
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="mess" rows="4"><?=$text?></textarea></div>
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Сохранить" />
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>
