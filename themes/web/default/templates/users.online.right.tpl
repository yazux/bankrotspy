<div class="right_panel_conf">
    <div class="menu_rt">Меню:</div>
    <?if(core::$rights==100):?>
        <div class="elmenu"><a href="<?=$home?>/control/allusers">Пользователи сайта</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/premusers">Пользователи c подпиской</a></div>
    <?endif?>
    <div class="elmenu"><a href="<?=$home?>/user/online">Пользователи онлайн</a></div>
    <div class="elmenu"><a href="<?=$home?>/user/guests">Гости онлайн</a></div>
    <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
    <div class="down_rmenu"> </div>
</div>