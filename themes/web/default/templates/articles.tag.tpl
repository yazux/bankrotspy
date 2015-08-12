<div class="content">
  <div class="conthead">
    <h2><i class="icon-tag"></i> <?=$tag?></h2>
  </div>

  <? temp::include('articles._lang.head.tpl') ?>
</div>  
   
<?if($out):?>   
  <?foreach($out as $out): ?>
    <? temp::include('articles.index.view.tpl') ?>
  <?endforeach?>
<?else:?>
   <div class = "simple_mess"><?=lang('no_stats')?></div>
<?endif?>

<?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>