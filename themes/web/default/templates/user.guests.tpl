<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-user-male"></i> Гости онлайн</h2></div>

                <?if($out):?>
                <?foreach($out as $out): ?>

                <div class="contbody_forms">
                    <table>
                        <tr>
                            <td style="width: 60px;">
                                <a href="<?=$home?>/user/profile?id=<?=$out['id']?>"><img class="avatar" src="<?=$out['avatar']?>"/></a>
                            </td>
                            <td>
                                <div class="ank">
                                    <b><?=$out['login']?></b>
                                    <hr/>
                                    <span class="under"><?=$out['usagent']?></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <?endforeach?>
                <?else:?>
                <div class="contbody_forms"><?=lang('no_users')?></div>
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