<div class="content">
  <div class="conthead">
    <h2>
      <?if($out):?>
        <?=lang('stats_p')?>&nbsp; <a href="<?=$home?>/user/profile?id=<?=$user_shid?>"><i class="icon-user-big"></i><?=$user_sh?></a>:
      <?else:?>
        <?=$user_sh?>
      <?endif?> 
    </h2>
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