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

            <form class='login_user_form' name='login_user' method="post" action="<?=$home?>/control/editgroup?id=<?=$id?>">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <div class="content">
                    <div class="conthead">
                        <h2><i class="icon-cog-alt"></i> Редактирование группы прав</h2>
                    </div>
                    <div class="contbody_forms">
                        <b>Название группы:</b> (В ед. числе)<br/>
                        <input name='gr_fullname' type="text" size="40" value="<?=$name?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>Краткое название группы:</b> (В ед. числе, макс. 5 букв)<br/>
                        <input name='gr_shortname' type="text" size="40" value="<?=$sname?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>ID группы:</b> (Число от 1 до 99, исключая уже существующие)<br/>
                        <input name='gr_id' class='login_user_input' type="text" size="40" value="<?=$sid?>"/>
                    </div>
                    <div class="contbody_forms">
                        <b>Права группы:</b><hr style="margin: 6px 0px"/>

                        <?if($regm):?>
                        <?foreach($regm as $key=>$value): ?>
                        <input name="rights[]" <?if(in_array($key,$nowreg)):?>checked="checked"<?endif?> value="<?=$key?>" type="checkbox"/> <?=$value?><br/>
                        <?endforeach?>
                        <?endif?>
                    </div>

                    <div class="contfin_forms">
                        <input name="submit" type="submit" value="Сохранить" />
                    </div>
                </div>
            </form>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню</div>
                <div class="elmenu"><a href="<?=$home?>/control/rights">Вернуться</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>