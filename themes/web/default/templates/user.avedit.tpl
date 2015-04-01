<table>
    <tr>
        <td valign="top">

            <?if ($error):?>
            <div class="error">
                <?foreach($error as $error): ?>
                <?=$error?>
                <?endforeach?>
            </div>
            <?endif?>

            <form name="mess" action="<?=$home?>/user/avedit?id=<?=$user_prof?>" method="POST" enctype="multipart/form-data">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-user-male"></i> Редактирование аватара</h2>
                    </div>
                    <div class="contbody_forms">
                        <img class="avatar_big" src="<?=$avatar?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('add_file')?></b><br/> <span class="under"><?=lang('add_info')?></span><hr/>
                        <input type="file" name="file"/>
                    </div>
                    <div class="contfin_forms">
                        <table><tr>
                                <td width="100px"><input type="submit" name="load" value="<?=lang('av_load')?>" /></td>
                                <td width="100px"><input type="submit" name="delete" value="<?=lang('av_del')?>" /></td>
                                <td>&nbsp;</td>
                            </tr></table>
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/user/profile?id=<?=$user_prof?>">Отмена</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
  