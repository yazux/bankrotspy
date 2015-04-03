<table >
    <tr>
        <td valign="top">

            <?if ($error):?>
            <br/><div class="error">
                <?foreach($error as $error): ?>
                <?=$error?>
                <?endforeach?>
            </div>
            <?endif?>

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-docs"></i>Отзывы/Предложения</h2>
                </div>

                <?if($com):?>
                <?foreach($com as $com): ?>

                <? temp::include('comms.class.show.tpl') ?>

                <?endforeach?>
                <?else:?>
                <div class="contbody_forms">
                    <br/>
                  Нет отзывов
                    <br/><br/>
                </div>
                <?endif?>
                <div class="contfin_forms">
                    <a class="urlbutton" href="<?=$home?>/feedback/write">Оставить отзыв/предложение</a>
                </div>
            </div>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>/feedback/write">Оставить отзыв/предложение</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
     
            


