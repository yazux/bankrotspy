<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-user-male"></i> <?=$name_prof?></h2>
                </div>
                <div class="contbody_forms">

                    <table>
                        <tr>
                            <td style="width: 60px;">
                                <a href="<?=$home?>/user/profile?id=<?=$user_prof?>"><img class="avatar" src="<?=$avatar?>"/></a>
                            </td>
                            <td>
                                <div class="ank">
                                    <b>Должность:</b> <span class="status"><?=$rights?></span><hr style="margin: 4px 0 6px 0"/>
                                    <b><?=lang('now')?></b>  <?if($online):?><span class="us_on"> <?=lang('lang_on_anc')?></span><?else:?><span class="us_off"> <?=lang('lang_off_anc')?></span><?endif?>

                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="contbody_forms">
                    <table class="lottable">
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark"><?=lang('name')?> </span></b><br/></td>
                            <td><?=$name ? $name : lang('none') ?></td>
                        </tr>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark"><?=lang('age')?></span></b><br/></td>
                            <td><?if($age):?><?=$age?> (<?=$zodiac?>)<?else:?><?=lang('none')?><?endif?></td>
                        </tr>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark"><?=lang('from')?></span></b><br/></td>
                            <td><?=$from ? $from : lang('none') ?></td>
                        </tr>
                        <?if($mail and core::$user_id):?>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark"><?=lang('us_mail')?></span></b><br/></td>
                            <td><?=$mail?></td>
                        </tr>
                        <?endif?>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark"><?=lang('site')?></span></b><br/></td>
                            <td><?=$site ? $site : lang('none') ?></td>
                        </tr>
                        <?if($mail and core::$user_id):?>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark">Номер телефона</span></b><br/></td>
                            <td><?=$phone ? $phone : lang('none') ?></td>
                        </tr>
                        <?endif?>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark">Скайп</span></b><br/></td>
                            <td><?if($icq):?><?=$icq?><?else:?><?=lang('none')?><?endif?></td>
                        </tr>
                        <tr>
                            <td style="width: 120px;"><b><span class="user_mark"><?=lang('interests')?></span></b><br/></td>
                            <td><?=$interests ? $interests : lang('none') ?></td>
                        </tr>
                        <tr>
                            <td valign="top" style="width: 120px;"><b><span class="user_mark"><?=lang('about')?></span></b><br/></td>
                            <td><?=$about ? $about : lang('none') ?></td>
                        </tr>
                    </table>

                </div>
                <div class="contfin_forms" style="padding: 9px 20px">

                </div>
            </div>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <?if($user_edit):?>
                <div class="elmenu"><a href="<?=$home?>/user/editprofile?id=<?=$user_prof?>"> Редактировать профиль</a></div>
                <div class="elmenu"><a href="<?=$home?>/user/avedit?id=<?=$user_prof?>"> <?=lang('av_edit')?></a></div>
                    <?if($can_del_user):?>
                <div class="elmenu"><a href="<?=$home?>/user/delete?id=<?=$user_prof?>"> <?=lang('delete')?></a></div>
                    <?endif?>
                <?endif?>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
