<table>
    <tr>
        <td valign="top">
            <div class="content">
            <div class="conthead"><h2><i class="icon-newspaper"></i> Новости сайта</h2></div>
            <?if($narr):?>
            <?foreach($narr as $narr):?>

                <div class="contbody_forms">
                    <div class="news_cont">
                        <div class='newsdown' style=" margin-top: 5px;font-size:13px;color:#a2a3a2;float:right;"> <?=$narr['date']?></div>
                        <div style="float: left; margin-top: 8px;">
                            <a href="<?=$home?>/user/profile?id=<?=$narr['user_id']?>"><img class="avatar" src="<?=$avatar?>"/></a>
                        </div>
                        <div style="float: left; margin-left: 20px;">
                            <a href="<?=$home?>/news/view?id=<?=$narr['id']?>"><h2><?=$narr['name']?></h2></a>
                            <?if($narr['new_news']):?><sup style="color:red;font-weight:bold;">[new]</sup><?endif?>
                            <a href="<?=$home?>/news/view?id=<?=$narr['id']?><?if($narr['page_to_go']):?>&amp;page=<?=$narr['page_to_go']?>#ncm<?else:?>#comms<?endif?>">
                                <i class="icon-comment-1"></i><?=$narr['comm_count']?>  
                                <?if($narr['new_comm_count']):?><span class="newred">+ <?=$narr['new_comm_count']?></span><?endif?>
                            </a>
                            <br />
                            <span style="color: #d27600; font-style: italic;"><?=$narr['keywords']?></span>
                        </div>
                        <div style="clear: both;"></div>
                        <hr/>
                        <?=$narr['text']?> ...
                        <hr/>
                        <a class="urlbutton_index button_no_top_index" href="<?=$home?>/news/view?id=<?=$narr['id']?>">Далее</a>
                    </div>
                </div>

                <div class="contfin_forms_delimiter">

                </div>

            <?endforeach?>
            <?else:?>
                <div class="contbody_forms">Нет ни одного пункта меню.</div>
            <?endif?>
            </div>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <?if($can_cr_news):?>
                <div class="elmenu"><a href="<?=$home?>/news/create">Добавить новость</a></div>
                <?endif?>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>