<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i>
                        <?if($out):?>
                        <?=lang('stats_p')?>&nbsp; <a href="<?=$home?>/user/profile?id=<?=$user_shid?>"><i class="icon-user-big"></i><?=$user_sh?></a>
                        <?else:?>
                        <?=$user_sh?>
                        <?endif?> </h2></div>


<?if($out):?>   
  <?foreach($out as $out): ?>
    <? temp::include('articles.index.view.tpl') ?>
  <?endforeach?>
<?else:?>
   <div class = "simple_mess"><?=lang('no_stats')?></div>
<?endif?>

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