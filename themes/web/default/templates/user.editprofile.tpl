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
            <form name="mess" action="?act=save&amp;id=<?=$user_prof?>" method="POST">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-user-male"></i> <?=$name_prof?></h2>
                </div>
                <?if ($edit_moder):?>
                <div class="contbody_forms">
                    <b><?=lang('nick')?></b><br/>
                    <span class="under"><?=lang('nick_und')?></span><br/>
                    <input type="text" name="nick" value="<?=$nick?>" />
                </div>
                <div class="contbody_forms">
                    <b><?=lang('rights')?></b><br/>
                    <select name="rights">
                        <?foreach($rights as $rig_id => $rig_name): ?>
                        <option value="<?=$rig_id?>" <?if($rig_id==$user_rights):?>selected="selected"<?endif?> ><?=$rig_name?></option>
                        <?endforeach?>
                    </select>
                </div>
                <div class="contbody_forms">
                    <b><?=lang('sex')?></b><br/>
                    <select name="sex">
                        <option value="m" <?if($sex=='m'):?>selected="selected"<?endif?> ><?=lang('sex_m')?></option>
                        <option value="w" <?if($sex=='w'):?>selected="selected"<?endif?> ><?=lang('sex_w')?></option>
                    </select>
                </div>
                <?endif?>

                <div class="contbody_forms">
                    <b><?=lang('name')?></b><br/>
                    <input type="text" name="name" value="<?=$name?>" />
                </div>
                <div class="contbody_forms">
                    <b><?=lang('birth')?></b><br/>
                    <input type="text" name="born_day" style="width: 50px;" size="2" placeholder="<?=lang('title_day')?>" title="<?=lang('title_day')?>" maxlength="2" value="<?=$born_day?>" />
                    <select name="born_month" style="width: 123px;" size="1">
                        <option value="0" ><?=lang('title_month')?></option>
                        <option value="1" <?if($born_month==1):?>selected="selected"<?endif?> ><?=lang('january')?></option>
                        <option value="2" <?if($born_month==2):?>selected="selected"<?endif?> ><?=lang('february')?></option>
                        <option value="3" <?if($born_month==3):?>selected="selected"<?endif?> ><?=lang('march')?></option>
                        <option value="4" <?if($born_month==4):?>selected="selected"<?endif?> ><?=lang('april')?></option>
                        <option value="5" <?if($born_month==5):?>selected="selected"<?endif?> ><?=lang('may')?></option>
                        <option value="6" <?if($born_month==6):?>selected="selected"<?endif?> ><?=lang('june')?></option>
                        <option value="7" <?if($born_month==7):?>selected="selected"<?endif?> ><?=lang('july')?></option>
                        <option value="8" <?if($born_month==8):?>selected="selected"<?endif?> ><?=lang('august')?></option>
                        <option value="9" <?if($born_month==9):?>selected="selected"<?endif?> ><?=lang('september')?></option>
                        <option value="10" <?if($born_month==10):?>selected="selected"<?endif?> ><?=lang('october')?></option>
                        <option value="11" <?if($born_month==11):?>selected="selected"<?endif?> ><?=lang('november')?></option>
                        <option value="12" <?if($born_month==12):?>selected="selected"<?endif?> ><?=lang('december')?></option>
                    </select>
                    <input type="text" style="width: 50px;" name="born_year" placeholder="<?=lang('title_year')?>" title="<?=lang('title_year')?>" size="4" maxlength="4" value="<?=$born_year?>" />
                </div>
                <div class="contbody_forms">
                    <b><?=lang('from')?></b><br/>
                    <input type="text" name="from" value="<?=$from?>" />
                </div>
                <div class="contbody_forms">
                    <b>Номер телефона</b><br/>
                    <input type="text" name="phone" value="<?=$phone?>" />
                </div>
                <div class="contbody_forms">
                    <b><?=lang('site')?></b><br/>
                    <input type="text" name="site" value="<?=$site?>" />
                </div>
                <div class="contbody_forms">
                    <b>Скайп</b><br/>
                    <input type="text" name="icq" value="<?=$icq?>" />
                </div>
                <div class="contbody_forms">
                    <b><?=lang('interests')?></b><br/>
                    <input type="text" name="interests" value="<?=$interests?>" />
                </div>
                <div class="contbody_forms">
                    <b><?=lang('about')?></b><br/>
                    <?=func::tagspanel('about');?>
                    <div class="texta"><textarea rows="10" name="about" id="about"><?=$about?></textarea></div>
                </div>
                <div class="contfin_forms">
                    <input type="submit" value="<?=lang('save')?>" />
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



  
  
  
  
  
  
  
  
  
  
  
  
  