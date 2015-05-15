<table >
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
                <div class="conthead">
                    <h2>Запрос в техподдержку #<?=$id?></h2>
                </div>
                <div class="contbody_forms">

                    <table style="margin-left:-6px; margin-right:-6px;">
                        <tr>
                            <td valign="top" width="50px">
                                <?if ($avatar):?>
                                <a href="<?=$home?>/user/profile?id=<?=$userid?>"><img class="avatar" src="<?=$home?><?=$avatar?>"/></a>
                                <?else:?>
                                <a href="<?=$home?>/user/profile?id=<?=$userid?>"><img class="avatar" src="<?=$themepath?>/images/user.png"/></a>
                                <?endif?>
                            </td>
                            <td>
                                <table style="margin-bottom:2px">
                                    <tr>
                                        <td width="100%">
                                            <a href="<?=$home?>/user/profile?id=<?=$userid?>"><b><?=$login?></b></a>
                                            <?if($us_tech_online):?><span class="us_on"> <?=lang('lang_on')?></span><?else:?><span class="us_off"> <?=lang('lang_off')?></span><?endif?><br/>
                                        </td>
                                        <td>
                                            <span class="time"><?=$time?></span>
                                        </td>
                                    </tr>
                                </table>
                                <div class="commtext"><?=$text?>
                                </div>

                            </td>
                        </tr>
                    </table>


                </div>


                    <?foreach($com as $com): ?>
                    <? temp::include('comms.class.show.tpl') ?>
                    <?endforeach?>


                <div class="contfin_forms">
                    <br/>
                </div>
                </div>

                <div class="content">
                    <?if(!$tech_close):?>
                    <div class="conthead">
                        <b>Добавить ответ:</b>
                    </div>
                    <form name="mess" action="<?=$home?>/support/view?id=<?=$id?>" method="post">
                        <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                        <div class="contbody_forms">
                            <?=func::tagspanel('messarea');?>
                            <div class="texta"><textarea id="messarea" name="msg" rows="5"></textarea></div>
                        </div>
                        <div class="contbody_forms"><i class="icon-attention"></i> Если Вам нужно прикрепить файл (скриншот), тогда воспользуйтесь бесплатными сервисами, например:
                            <a href="http://pixs.ru/">http://pixs.ru/</a> или
                            <a href=""https://gyazo.com/ru>https://gyazo.com/ru</a> и вставьте ссылку на изображение в сообщение.
                        </div>
                        <div class="contfintext">
                            <input type="submit" name="submit" size="14" value="Отправить" />
                        </div>
                    </form>
                    <?else:?>
                    <div class="contbody_forms">Обращение закрыто.</div>
                    <?endif?>
                </div>


        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Техподдержка</div>
                <?if(!$tech_close):?>
                <div class="elmenu"><a href="<?=$home?>/support/close?id=<?=$id?>">Закрыть обращение</a></div>
                <?endif?>
                <div class="elmenu"><a href="<?=$home?>/support">Вернуться</a></div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
