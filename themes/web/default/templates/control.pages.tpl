<table>
    <tr>
        <td valign="top">

<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> Страницы</h2>
    </div>

    <?if($rmenu):?>

    <?foreach($rmenu as $rmenu): ?>
    <div class="contbody_forms">
        <table>
            <tr>
              <td style="width: 30px;"><i class="icon-docs"></i></td>
              <td>
                  <b><a href="<?=$home?>/pages/<?=$rmenu['id']?>"><?=$rmenu['name']?></a></b><br/>
              </td>
                <td class="cont_act"><a title="<?=lang('edit_link')?>" href="<?=$home?>/pages/statedit?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                <td class="cont_act"><a title="<?=lang('del_link')?>" href="<?=$home?>/pages/statdel?id=<?=$rmenu['id']?>"><i class="icon-delete"></i></a></td>
            </tr>
        </table>
    </div>
    <?endforeach?>

    <?else:?>
        <div class="contbody_forms">Нет ни одного пункта меню.</div>
    <?endif?>

    <div class="contfin">
        <a class="urlbutton" href="<?=$home?>/pages/add">Создать страницу</a>
    </div>
</div>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>
