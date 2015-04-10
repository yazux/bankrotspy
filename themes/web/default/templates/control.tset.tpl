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
                  Доступ, мес: <?=$rmenu['longtime']?><br/>
                  <span style="font-size: 13px;color: #b1aea8;"><?=$rmenu['subtext']?></span>
              </td>
                <td class="cont_act"><a title="Редактировать" href="<?=$home?>/control/tarifedit?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                <td class="cont_act"><a title="Удалить" href="<?=$home?>/control/tarifdel?id=<?=$rmenu['id']?>"><i class="icon-delete"></i></a></td>
            </tr>
        </table>
    </div>
    <?endforeach?>

    <?else:?>
        <div class="contbody_forms">Нет ни одного пункта меню.</div>
    <?endif?>

    <div class="contfin">
        <a class="urlbutton" href="<?=$home?>/control/newtarif">Новый тариф</a>
    </div>
</div>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>
