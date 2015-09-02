<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> <?=lang('keywords_p')?></h2></div>


  <?if($out):?>
    <?foreach($out as $out): ?>
    <div class="contbody_und"> 
       <span class="keytitle">
         <a href="<?=$home?>/articles/tag<?=$out['id']?>"><i class="icon-tag"></i> <?=$out['name']?></a>
         <hr/>
         <span class="keyund"><?=lang('col_stats')?> <?=$out['count']?></span>
       </span>
    </div> 
    <?endforeach?>
  <?else:?>
    <div class="contbody_und"><?=lang('no_cats')?></div>
  <?endif?>
  
  <div class="contfin">
      <div class="total_info"><b><?=lang('total_p')?></b> <?=$total?></div>
  </div>      



<?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>


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