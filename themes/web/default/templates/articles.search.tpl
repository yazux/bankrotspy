<div class="content">
    <div class="conthead">
        <h2><i class="icon-doc-text-inv icon-theme"></i><?=lang('stats')?></h2>
    </div>
    <? temp::include('articles._lang.head.tpl') ?>
</div>

<div class="content">
    <div class="contbody_forms">
        <form action="<?=$home?>/articles/search">
            <input type="text" name="q" value="<?=$item?>"> <input class="butt_like_area" type="submit" value="<?=lang('search_it')?>" />
        </form>
    </div>
</div>
   
<?if($out):?>   
  <?foreach($out as $out): ?>
    <? temp::include('articles.index.view.tpl') ?>
  <?endforeach?>
<?elseif($item_exists):?>
   <div class = "simple_mess"><?=lang('no_found')?></div>
<?else:?>
   <div class = "simple_mess"><?=lang('lets_search')?></div>
<?endif?>

<?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>