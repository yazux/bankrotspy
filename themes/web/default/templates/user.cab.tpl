<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-user-male"></i> Личный кабинет</h2>
                </div>
                <div class="contbody_forms">

                    <table>
                        <tr>
                            <td style="width: 60px;">
                                <a href="<?=$home?>/user/profile?id=<?=$user_prof?>"><img class="avatar" src="<?=$avatar?>"/></a>
                            </td>
                            <td>
                                <div class="ank">
                                    <b>Должность:</b> <span class="status"><?=$rights?></span><hr style="margin: 4px 0 6px 0"/>
                                    <b><?=lang('now')?></b>  <?if($online):?><span class="us_on"> <?=lang('lang_on_anc')?></span><?else:?><span class="us_off"> <?=lang('lang_off_anc')?></span><?endif?>

                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="contbody_forms">
                    <table class="lottable">
                        <tr>
                            <td style="width: 180px;"><b><span class="user_mark">Тарифный план: </span></b><br/></td>
                            <td><?if($tariff):?><b><?=$tariff?></b><?else:?>нет<?endif?></td>
                        </tr>
                        <tr>
                            <td style="width: 180px;"><b><span class="user_mark">Номер заказа: </span></b><br/></td>
                            <td><?=$ordercode ? $ordercode : 'нет' ?> </td>
                        </tr>
                        <tr>
                            <td style="width: 180px;"><b><span class="user_mark">Срок окончания: </span></b><br/></td>
                            <td><?=$desttime ? $desttime : 'нет' ?> </td>
                        </tr>
                        <tr>
                            <td style="width: 180px;"><b><span class="user_mark">E-mail для оповещений: </span></b><br/></td>
                            <td><?=core::$user_mail ?> <span style="font-size: 13px;color: #8c8989;">(виден только вам)</span></td>
                        </tr>
                    </table>

                </div>
                <div class="contfin_forms">
                    <form name="mess" action="<?=$home?>/exit" method="POST">
                        <input type="hidden" name="act" value="do"/>
                        <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                        <input type="submit" value="<?=lang('exit')?>" />
                    </form>
                </div>
            </div>

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-search"></i> Поисковые профили</h2>
                </div>
                <div class="contbody_forms">
                    <b>Поисковые профили</b> - это инструмент для отслеживания большого количества лотов по заданным поисковым параметрам. Более подробное описание в разделе <a href="<?=$home?>/pages/1">Помощь</a>.
                </div>
                <?if($outprofiles):?>

                <?foreach($outprofiles as $rmenu): ?>
                <div class="contbody_forms">
                    <table>
                        <tr>
                            <td valign="top" style="width: 30px;padding-top: 6px"><i class="icon-list"></i></td>
                            <td>
                                <b><?=$rmenu['name']?></b><br/>
                                <a href="<?=$home?>/user/loadrofile?id=<?=$rmenu['id']?>">Загрузить профиль</a>
                            </td>
                            <?if($rmenu['can_edit']):?>
                                <td class="cont_act"><a title="Редактировать" href="<?=$home?>/user/renameprofile?id=<?=$rmenu['id']?>"><i class="icon-edit"></i></a></td>
                                <td class="cont_act"><a title="Удалить" href="<?=$home?>/user/deleteprofile?id=<?=$rmenu['id']?>"><i class="icon-delete"></i></a></td>
                            <?endif?>
                        </tr>
                    </table>
                </div>
                <?endforeach?>

                <?else:?>
                <div class="contbody_forms" style="padding-top: 20px;padding-bottom: 20px;">Нет ни одного поискового профиля.</div>
                <?endif?>
                <div class="contfin_forms">
                    <a href="<?=$home?>/user/newprofile" class="urlbutton_index">Создать новый</a>
                </div>
            </div>


        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/user/profile"><b>Мой профиль</b></a></div>
                <div class="elmenu"><a href="<?=$home?>/user/chpass"><?=lang('ch_pass')?></a></div>
                <div class="elmenu"><a href="<?=$home?>/user/chmail"><?=lang('ch_mail')?></a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
