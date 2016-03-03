<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-cog-alt"></i> Платежи</h2>
                </div>
                
                <div class="contbody_forms">
                    <form style="display:block;margin-bottom:20px;" method="get">
                        <input type="text" placeholder="логин" name="search" value="<?=$search?>" style="height: 15px;">
                        <input class="urlbutton_index button_no_top_index" type="submit" value="искать">
                    </form>
                </div>
                
                <div class="contbody_forms">
                    <?if($out):?>
                        <b><?=lang('uall')?> <?=$uall?></b><br/>
                    <?else:?>
                        <br/>Нет оплат<br/><br/>
                    <?endif?>
                </div>
            </div>

            <?if($out):?>
                <?foreach($out as $key_year => $year): ?>

                <?if($year):?>
                <?foreach($year as $key_month => $month): ?>

                <div class="content">
                    <div class="conthead">
                        <h2><?=$marr[$key_month]?> <?=$key_year?></h2>
                    </div>
                    <div class="contbody_forms">
                        <style>
                            .pays_t td {
                                /*border-bottom:1px solid #eee;*/
                            }
                            .pays_t tr:hover{
                                background:#f9f8f8;
                            }
                        </style>
                        <table class="pays_t">

                        <?if($month):?>
                        <?foreach($month as $id => $data): ?>
                        <tr>
                            <td width="10" style="text-align:center; pading:0 3px;"><?= $id+1 ?></td>
                            <td style="text-align:left;"><b><a href="<?=$home?>/user/profile?id=<?=$data['userid']?>"><?=$data['username']?></a></b></td>
                            <td style="text-align:left;"><?=$data['summ']?> р.</td>
                            <td style="text-align:left;"><?=$data['paidid']?></td>
                            <td style="text-align:left;"><?=$data['paytime']?></td>
                            <td style="text-align:left;"><?=$data['time']?></td>
                            <td style="text-align:left;"><?=$data['comm']?></td>
                        </tr>
                        <?endforeach?>
                        <tr style="border-top: 1px solid #dbd5d7;">
                            <td colspan="6"><b>Итого за месяц:</b> <b><?=$msumm[$key_month]?> p.</b></td>
                        </tr>
                        <?endif?>
                        </table>
                    </div>
                </div>

                    <?endforeach?>
                <?endif?>
                <?endforeach?>

                <!--/div-->
            <?endif?>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>

        </td>
        <td class="right_back_menu">
            <? temp::include('control.index.right.tpl') ?>
        </td>
    </tr>
</table>