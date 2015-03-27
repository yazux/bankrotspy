<table>
    <tr>
        <td valign="top">

            <?if ($error):?>
            <div class="error">
                <?foreach($error as $error): ?>
                <?=$error?><br/>
                <?endforeach?>
            </div>
            <?endif?>

            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> Новости сайта</h2></div>
                <div class="contbody_forms">
                    <div class="news_cont">
                        <h2><?=$newname?></h2>
                        <hr/>
                        <?=$text?> ...
                        <hr/>
                        <table>
                            <tr>
                                <td><div class='newsdown'>Добавил: <a href="<?=$home?>/user/profile?id=<?=$user_st_id?>"><i class="icon-user-male"></i> <?=$login?></a></div></td>
                                <td width="170px;"><div class='newsdown' style="font-size:13px;color:#a2a3a2;"> <?=$time?></div></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


            <div class="content" style="margin-top: 18px;">
                <div class="conthead"><b><?=lang('comments')?> (<?=$total?>)</b> </div>
                <?foreach($com as $com): ?>

                <? temp::include('comms.class.show.tpl') ?>

                <?endforeach?>

                <?if($its_user):?>
                <form name="mess" action="<?=$home?>/news/view?id=<?=$id?>" method="post">
                    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                    <div class="contbody_forms">
                        <?=lang('add_com')?><br/>
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="msg" rows="5"></textarea></div>
                    </div>

                    <div class="contfintext">
                        <input type="submit" name="submit" size="14" value="<?=lang('send')?>" />
                    </div>
                </form>
                <?else:?>
                <div class="contfintext"><?=lang('reg_now')?></div>
                <?endif?>
            </div>


            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <?if($can_ed_news):?>
                    <div class="elmenu"><a href="<?=$home?>/news/edit?id=<?=$id?>">Редактировать новость</a></div>
                <?endif?>
                <?if($can_del_news):?>
                    <div class="elmenu"><a id="del_news">Удалить новость</a></div>
                <?endif?>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>

<?if($can_del_news):?>
<div id="popup_overlay_delnews" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <div class="main_pop_head">
            Удаление новости
        </div>
        <div class="main_pop_body" id="platform_table">
            Вы действительно желаете удалить новость?
        </div>
        <div class="main_pop_bottom">
            <form method="post" action="<?=$home?>/news/delete?id=<?=$id?>">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input  name="submit" type="submit" value="Удалить" />
                <span id="delnews_popup_close" class="urlbutton_index">Отмена</span>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '#delnews_popup_close', function(){
        $('#popup_overlay_delnews').fadeOut(200);
    });
    $(document).on('click', '#del_news', function(){
        $('#popup_overlay_delnews').fadeIn(200);
    });
</script>
<?endif?>


        

     
            


