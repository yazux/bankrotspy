<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-user-male"></i> <?=lang('u_online')?></h2></div>

                <?if($out):?>
                <?foreach($out as $out): ?>
                <div class="contbody_forms">
                <table>
                    <tr>
                        <td style="width: 60px;">
                            <?if(core::$user_id):?>
                            <a href="<?=$home?>/user/profile?id=<?=$out['id']?>"><img class="avatar" src="<?=$out['avatar']?>"/></a>
                            <?else:?>
                            <img class="avatar" src="<?=$out['avatar']?>"/>
                            <?endif?>
                        </td>
                        <td>
                            <div class="ank">
                                <?if(core::$user_id):?>
                                <a href="<?=$home?>/user/profile?id=<?=$out['id']?>"><b><?=$out['login']?></b></a>
                                <?else:?>
                                <b><?=$out['login']?></b>
                                <?endif?>
                                <hr/>
                                <span class="status"><?=$out['rights']?></span><br/>
                                <?=lang('now')?> <?if($out['online']):?><span class="us_on"> <?=lang('lang_on_anc')?></span><?else:?><span class="us_off"> <?=lang('lang_off_anc')?></span><?endif?>
                            </div>
                        </td>
                    </tr>
                </table>
                </div>
                <?endforeach?>
                <?else:?>
                <div class="contbody_forms">Нет пользователей с подпиской!</div>
                <?endif?>
                <div class="contfin_forms" style="padding: 9px 20px">
                    Всего: <?=$total_in?>
                </div>

            </div>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
        </td>
        <td class="right_back_menu">
                <? temp::include('users.online.right.tpl') ?>
        </td>
    </tr>
</table>