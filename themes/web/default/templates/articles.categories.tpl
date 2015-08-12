<div class="content">
  <div class="conthead">
    <h2><i class="icon-doc-text-inv icon-theme"></i><?=lang('stats')?></h2>
  </div>

  <? temp::include('articles._lang.head.tpl') ?>
</div>

<div class="content">
  <div class="conthead">
    <b><?=lang('keywords_p')?></b>
  </div>
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
</div>


<?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>