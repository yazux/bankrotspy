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

            <form name="mess" action="?" method="POST">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input type="hidden" name="act" value="do"/>

                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-user-male"></i> Изменение <?=lang('ch_mail')?></h2>
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('old_pass')?></b><br/>
                        <input type="password" name="orig_pass" value="" />
                    </div>
                    <div class="contbody_forms">
                        <b><?=lang('new_mail')?></b><br/>
                        <span class="under"><?=lang('inf_mail')?></span><br/>
                        <input type="text" name="mail" value="" />
                    </div>
                    <div class="contfin_forms">
                        <input type="submit" value="<?=lang('change')?>" />
                    </div>
                </div>

            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/user/cab">Отмена</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>

  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
