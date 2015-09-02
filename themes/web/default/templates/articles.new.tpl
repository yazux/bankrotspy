<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> <?=lang('stats')?></h2></div>


   
<?if($out):?>   
  <?foreach($out as $out): ?>
    <? temp::include('articles.index.view.tpl') ?>
  <?endforeach?>
<?else:?>
   <div class = "contbody_forms"><br/><?=lang('no_stats')?><br/><br/></div>
<?endif?>

<?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>

                </div>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Статьи:</div>
                <? temp::include('articles._lang.head.tpl') ?>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>