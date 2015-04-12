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
                <div class="conthead">
                    <h2>Активные обращения:</h2>
                </div>

                    <div style="margin: 0px 0px 0px 0px">
                        <?if($narr):?>
                        <div class="all_sup">
                            <table class="supp_table">
                                <tr class="headt">
                                    <td style="width: 90px">Номер</td>
                                    <td style="width: 170px">Дата обращения</td>
                                    <td>Тема обращения</td>
                                    <td style="width: 150px">Статус</td>
                                </tr>
                                <?foreach($narr as $narr):?>
                                <tr class="linet">
                                    <td class="firstt"><?=$narr['id']?></td>
                                    <td><center><span class="gray" style="font-size: 13px;"><?=$narr['date']?></span></center></td>
                                    <td style="padding-left: 3px;"><a class="main_tlink" href="<?=$home?>/support/view?id=<?=$narr['id']?>"><?=$narr['text']?></a></td>
                                    <td style="text-align: center;">
                                        <?if($narr['read'] == 0):?>
                                        <span style="color:red;">Новое обращение</span>
                                        <?elseif($narr['read'] == 1 AND $narr['usread'] == 0):?>
                                        <span style="color:green;">Ответ отправлен</span>
                                        <?elseif($narr['read'] == 1 AND $narr['usread'] == 1):?>
                                        <span style="color:gray;">Ожидание закрытия</span>
                                        <?endif?>
                                    </td>
                                </tr>
                                <?endforeach?>
                            </table>
                        </div>
                        <?else:?>
                        <div class="contbody_forms">Нет активных обращений</div>
                        <?endif?>
                    </div>


                <div class="contfin_forms">
                    <br/>
                </div>
            </div>


            <div class="content" style="margin-top: 20px;">
                <div class="conthead">
                    <h2>Закрытые обращения:</h2>
                </div>
                <div style="margin: 0px 0px 0px 0px">

                    <?if($narr2):?>
                    <div class="all_sup">
                        <table class="supp_table">
                            <tr class="headt">
                                <td style="width: 90px">Номер</td>
                                <td style="width: 170px">Дата обращения</td>
                                <td>Тема обращения</td>
                                <td style="width: 150px">Статус</td>
                            </tr>
                            <?foreach($narr2 as $narr2):?>
                            <tr class="linet">
                                <td class="firstt"><?=$narr2['id']?></td>
                                <td><center><span class="gray" style="font-size: 13px;"><?=$narr2['date']?></span></center></td>
                                <td style="padding-left: 3px;"><a class="main_tlink" href="<?=$home?>/support/view?id=<?=$narr2['id']?>"><?=$narr2['text']?></a></td>
                                <td style="text-align: center;"> <span style="color:brown;">Закрыто</span> </td>
                            </tr>
                            <?endforeach?>
                        </table>
                    </div>
                    <?else:?>
                    <div class="contbody_forms">Нет закрытых обращений</div>
                    <?endif?>

                </div>
                <div class="contfin_forms">
                    <br/>
                </div>
            </div>
            <script type="text/javascript">
                $(document).on('click', '.supp_table tr.linet', function(){
                    window.location = $(this).find('.main_tlink').attr('href');
                });
            </script>

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Техподдержка</div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
