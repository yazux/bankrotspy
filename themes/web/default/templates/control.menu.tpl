<table>
    <tr>
        <td>

<div class="content">
    <div class="conthead">
        <h2><i class="icon-cog-alt"></i> <?=lang('site_menu')?></h2>
    </div>

    <?if($rmenu):?>

    <?foreach($rmenu as $rmenu): ?>
    <div class="contbody_forms">
        <table>
            <tr>
              <td style="width: 30px;"><i class="icon-globe-bigmenu"></i></td>
              <td>
                  <b><?=$rmenu['name']?></b><br/>
                  <a target="_blank" href="<?=$rmenu['link']?>"><?=$rmenu['link']?></a>
              </td>
                <td class="cont_act"><a title="<?=lang('edit_link')?>" href="<?=$home?>/control/menuedit?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                <td class="cont_act"><a title="<?=lang('del_link')?>" href="<?=$home?>/control/menudel?id=<?=$rmenu['id']?>"><i class="icon-delete"></i></a></td>
            </tr>
        </table>
    </div>
    <?endforeach?>

    <?else:?>
        <div class="contbody_forms">Нет ни одного пункта меню.</div>
    <?endif?>

    <div class="contfin">
        <a class="urlbutton" href="<?=$home?>/control/newmenu"><?=lang('new_item')?></a>
    </div>
</div>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>
