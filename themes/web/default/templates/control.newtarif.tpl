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

            <form name="mess" action="<?=$home?>/control/newtarif" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i> Новый тариф</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>Название тарифа:</b><br/>
                        <input name="name" type="text" value="<?=$name?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>Цена, руб.:</b><br/>
                        <input name="price" type="text" value="<?=$price?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>Продолжительность действия, мес.:</b><br/>
                        <input name="longtime" type="text" value="<?=$longtime?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>Права для тарифа</b><br/>
                        <select name="rights">
                            <?foreach($rights as $rig_id => $rig_name): ?>
                            <?if(in_array($rig_id, array(10, 11))):?>
                            <option value="<?=$rig_id?>" <?if($rig_id==$user_rights):?>selected="selected"<?endif?> ><?=$rig_name?></option>
                            <?endif?>
                            <?endforeach?>
                        </select>
                    </div>
                    <div class="contbody_forms">
                        <b>Краткое описание тарифа:</b><br/>
                        <?=func::tagspanel('messarea');?>
                        <div class="texta"><textarea id="messarea" name="descr" rows="4"><?=$descr?></textarea></div>
                    </div>
                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Создать" />
                    </div>
                </div>
            </form>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt"><?=lang('menu_settings')?>:</div>
                <div class="elmenu"><a href="<?=$home?>/control/tset">Вернуться</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>