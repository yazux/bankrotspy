    <table class="contbutton">
      <tr>

        <?if(core::$action === 'index'):?>
          <td><div class="nowselpun"><?=lang('last_stats')?></div></td>
        <?else:?>
          <td><a href="<?=$home?>/articles"><?=lang('last_stats')?></a></td>
        <?endif?>

        <?if(core::$action == 'allarticles'):?>
        <td><div class="nowselpun"><?=lang('allarticles_stat')?> </div></td>
        <?else:?>
        <td><a href="<?=$home?>/articles/allarticles"><?=lang('allarticles_stat')?></a></td>
        <?endif?>

        <?if(core::$action == 'categories'):?>
          <td><div class="nowselpun"><?=lang('cat_stat')?></div></td>
        <?else:?>
          <td><a href="<?=$home?>/articles/categories"><?=lang('cat_stat')?></a></td>
        <?endif?>
        
        <?if(core::$action == 'favorites' AND core::$user_id):?>
          <td><div class="nowselpun"><?=lang('favorites')?></div></td>
        <?elseif(core::$user_id):?>
          <td><a href="<?=$home?>/articles/favorites"><?=lang('favorites')?></a></td>
        <?endif?>

        <?if($onmoder_stat_head):?>
          <td><a class="nowselpun_gray" href="<?=$home?>/articles/draft"><?=lang('draft')?></a></td>
        <?elseif(core::$action == 'draft' AND core::$user_id AND $can_cr_stat):?>
          <td><div class="nowselpun"><?=lang('draft')?></div></td>
        <?elseif(core::$user_id AND $can_cr_stat):?>
          <td><a href="<?=$home?>/articles/draft"><?=lang('draft')?></a></td>
        <?endif?>

        <?if($can_cr_stat):?>
          <?if(core::$action == 'create'):?>
            <td class="conlast"><div class="nowselpun"><?=lang('create_stat')?></div></td>
          <?else:?>
            <td class="conlast"><a href="<?=$home?>/articles/create"><?=lang('create_stat')?></a></td>
          <?endif?>
        <?endif?>

        <?if(core::$action == 'search'):?>
          <td><div class="nowselpun"><?=lang('search_text')?></div></td>
        <?else:?>
          <td><a href="<?=$home?>/articles/search"><?=lang('search_text')?></a></td>
        <?endif?>

        <?if(core::$action == 'onmoder'):?>
          <td><div class="nowselpun"><?=lang('onmoder')?></div></td>
        <?endif?>
        <?if($onmoder_show_buttons):?>
          <td><a class="nowselpun_gray" href="<?=$home?>/articles/onmoder"><?=lang('onmoder')?></a></td>
        <?endif?>

        <td class="contbfin">&nbsp;</td>
        
      </tr>
    </table>