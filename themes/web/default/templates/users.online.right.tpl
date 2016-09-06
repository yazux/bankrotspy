<div class="right_panel_conf">
    <div class="menu_rt">Меню:</div>
    <?if(core::$rights>=100):?>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=100">Администраторы</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=90">Редакторы</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=70">Техподдержка</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=20">Партнер</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=111">Наблюдатель</a></div>
        
        <div class="elmenu"><a href="<?=$home?>/control/allusers">Пользователи (все)</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=0">Пользователи без подписки</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=10">Пользователи с подпиской</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=11">Пользователи с VIP-подпиской</a></div>
        <div class="elmenu"><a href="<?=$home?>/control/allusers?rights=-1">Заблокированные</a></div>
        <!--div class="elmenu"><a href="<?=$home?>/control/premusers">Пользователи c подпиской</a></div-->
    <?endif?>
    <div class="elmenu"><a href="<?=$home?>/user/online">Пользователи онлайн</a></div>
    <div class="elmenu"><a href="<?=$home?>/user/guests">Гости онлайн</a></div>
    <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
    <div class="down_rmenu"> </div>
</div>