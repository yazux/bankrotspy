

        <?if(core::$action === 'index'):?>

        <?else:?>
        <!--<div class="elmenu"><a href="<?=$home?>/articles"><?=lang('last_stats')?></a></div>-->
        <?endif?>

        <?if(core::$action == 'allarticles'):?>

        <?else:?>
        <!--<div class="elmenu"><a href="<?=$home?>/articles/allarticles"><?=lang('allarticles_stat')?></a></div>-->
        <?endif?>
        <div class="elmenu"><a href="<?=$home?>/articles/allarticles"><?=lang('allarticles_stat')?></a></div>

        <?if(core::$action == 'categories'):?>

        <?else:?>

        <?endif?>

        <div class="elmenu"><a href="<?=$home?>/articles/categories"><?=lang('cat_stat')?></a></div>

        <?if(core::$action == 'favorites' AND core::$user_id):?>
        <?elseif(core::$user_id):?>
        <!--<div class="elmenu"><a href="<?=$home?>/articles/favorites"><?=lang('favorites')?></a></div>-->
        <?endif?>

        <!--<?if($onmoder_stat_head):?>
        <div class="elmenu"><a href="<?=$home?>/articles/draft"><?=lang('draft')?></a></div>
        <?elseif(core::$action == 'draft' AND core::$user_id AND $can_cr_stat):?>

        <?elseif(core::$user_id AND $can_cr_stat):?>
        <div class="elmenu"><a href="<?=$home?>/articles/draft"><?=lang('draft')?></a></div>
        <?endif?>-->

        <?if($can_cr_stat):?>

        <div class="elmenu"><a href="<?=$home?>/articles/create"><?=lang('create_stat')?></a></div>

        <?endif?>

        <?if(core::$action == 'search'):?>

        <?else:?>

        <?endif?>
        <div class="elmenu"><a href="<?=$home?>/articles/search"><?=lang('search_text')?></a></div>
        <?if(core::$action == 'onmoder'):?>

        <?endif?>

        <div class="elmenu"><a  href='<?=$home?>/articles/new'><?=lang('new_stat')?> <?if(counts::get('unread_articles')):?><span class="newred">+<?=counts::get('unread_articles')?></span><?endif?></a></div>
        <div class="elmenu"><a  href='<?=$home?>/articles/newcomm'><?=lang('new_comms')?> <?if(counts::get('unread_art_comms')):?><span class="newred">+<?=counts::get('unread_art_comms')?></span><?endif?></a></div>


        <?if($onmoder_show_buttons):?>
        <div class="elmenu"><a class="nowselpun_gray" href="<?=$home?>/articles/onmoder"><?=lang('onmoder')?></a></div>
        <?endif?>
