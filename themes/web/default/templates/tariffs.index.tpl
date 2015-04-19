<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-key"></i>Тарифы</h2>
                </div>
                <div class="contbody_forms">
                   <?=$text?>
                </div>

            </div>

            <?if($rmenu):?>

            <?foreach($rmenu as $rmenu): ?>

            <div class="tarbody">
                <table >
                    <tr>
                        <td valign="top" width="100%">
                            <h2 class="tarhead"><i class="icon-briefcase"></i> <?=$rmenu['name']?></h2>
                            <span class="costpt">Стоимость подписки: <?=$rmenu['price']?> руб.</span>
                            <span class="undertartext"><?=$rmenu['subtext']?></span>
                        </td>
                        <td><img src="<?=$themepath?>/images/PSKB.png"/></td>

                    </tr>
                </table>
                <div class="button_div">
                    <hr style="margin-bottom: 13px" />
                    <?if(core::$user_id AND !CAN('paycontent', 0)):?>

                    <form method="post" action="<?=$oos_payment_page?>">
                        <div style="display:none">
                            <p> <input name="marketPlace" value="<?=$rmenu['params']['marketPlace']?>"/>    </p>
                            <p> <input name="message" value="<?=$rmenu['params']['message']?>"/> </p>
                            <p> <input name="signature" value="<?=$rmenu['params']['signature']?>"/> </p>
                        </div>
                        <input style="display:block;margin-left: 0px;margin-top:7px;padding-left: 15px;padding-right: 15px;" class="reg_search_butt" name="submit" type="submit" value="Оплатить" />
                    </form>

                    <?elseif(!core::$user_id):?>
                    <a style="margin-left: 0px;margin-top:7px;padding-left: 15px;padding-right: 15px;" class="urlbutton_index" href="<?=$home?>/user/register" >Зарегистрироваться и оплатить</a>
                    <?else:?>
                    <span style="color: #a6a4a4;display: block;margin-left: 7px;margin-bottom: 10px;">У вас уже есть подписка.</span>
                    <?endif?>
                </div>
            </div>

            <?endforeach?>

            <?else:?>
                <div class="content">
                    <div class="contbody_forms">Нет ни одного пункта меню.</div>
                </div>
            <?endif?>


        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>