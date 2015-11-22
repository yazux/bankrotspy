<table>
    <tr>
        <td class="right_back_menu_index_set">
            <span id="on_load_new_page"></span>
            <div class="right_panel_conf">
                <div class="menu_rt_index">Настройка:</div>
                <div class="news_back" id="bs_set_container">
                    <table>
                        <tr>
                            <td valign="top">
                                <i class="icon-location"></i> <b>Регионы:</b> выбрано <span id="total_places_set"><?=count($places_used)?></span> из <?=count($places_def)?> <span id="region_set" class="change_block_set"><i class="icon-edit-orange"></i>Изменить</span>
                            </td>
                            <td valign="top" style="border-right: 0">
                                <i class="icon-globe-set"></i> <b>Площадки:</b> выбрано <span id="total_platforms_set"><?=count($platforms_used)?></span> из <?=count($platforms_def)?> <span id="platform_set" class="change_block_set"><i class="icon-edit-orange"></i>Изменить</span>
                            </td>
                        </tr>
                    </table>

                    <hr class="super_hr"/>
                    <table>
                        <tr>
                           <td valign="top">
                               <i class="icon-search"></i> Поиск по названию:<br/>
                               <input type="text" name="svalname"/>

                               <hr/>
                               <table>
                                   <tr>
                                     <td style="width: 50%;">
                                         <i class="icon-hammer-set"></i> Тип торгов:<br/>
                                         <?foreach($types_set as $tkey=>$tvalue): ?>
                                         <label><input type="checkbox" <?if($types_def[$tkey] == 1):?> checked="checked" <?endif?> name="type_auct_<?=$tkey?>"/><?=$tvalue?></label><br/>
                                         <?endforeach?>
                                     </td>
                                     <td style="border-right: 0;white-space: nowrap;width: 50%;">
                                         <i class="icon-clock"></i> Статус:<br/>
                                         <label><input type="checkbox" name="status_auct_1"/>Объявленные</label><br/>
                                         <label><input type="checkbox" name="status_auct_2"/>Приём заявок</label><br/>
                                         <label><input type="checkbox" name="status_auct_3"/>Окончены</label><br/>
                                     </td>
                                   </tr>
                               </table>
                           </td>
                           <td valign="top">
                               <i class="icon-calendar"></i> Дата подачи:<br/>
                               <table class="nomarginnews">
                                   <tr>
                                       <td style="width: 28px;">С: </td><td style="white-space: nowrap;"><input type="text" name="begin_set_date"/></td>
                                   </tr>
                                   <tr>
                                       <td>По: </td><td style="white-space: nowrap;" ><input type="text" name="end_set_date"/></td>
                                   </tr>
                               </table>

                               <hr/>
                               <input style="width: 37px;" onmouseover="toolTip('Число или интервал,<br/> например: 2-5<hr/>Нельзя одновременно использовать Дату подачи и эту функцию.')" onmouseout="toolTip()" type="text" name="altintconf"/>
                               <span style="font-size: 13px">Дней до подачи</span>
                               <br/>
                               
                               <label><input type="checkbox" name="new_lots" <?= !empty($new_lots) ? 'checked' : '' ?>/>&nbsp;&nbsp;Новые лоты за 72 час.</label>
                           </td>
                           <td valign="top" style="border-right: 0">
                               <i class="icon-rouble"></i> Цена лота:<br/>
                               <table class="nomarginnews">
                                   <tr>
                                       <td style="width: 28px;">С: </td><td><input type="text" id="price_start_forid" onkeyup="number_format(this.id);" name="price_start"/></td>
                                   </tr>
                                   <tr>
                                       <td>По: </td><td><input type="text" id="price_end_forid" onkeyup="number_format(this.id);" name="price_end"/></td>
                                   </tr>
                               </table>

                               <hr style="margin: 3px"/>

                               <label><input type="radio" <?if($type_price == 1):?>checked="checked"<?endif?> name="type_price" value="1"/> Начальная цена</label><br/>
                               <label><input type="radio" <?if($type_price == 2):?>checked="checked"<?endif?> name="type_price" value="2"/> Текущая цена</label><br/>
                               <label onmouseover="toolTip('Рыночная цена является оценочной и присутствует не у всех лотов.')" onmouseout="toolTip()"><input  type="radio" <?if($type_price == 3):?>checked="checked"<?endif?> name="type_price" value="3"/> Рыночная цена</label><br/>
                           </td>
                        </tr>
                    </table>
                     <hr class="news_back_hr" />
                        <table class="ch_profile_menu">
                            <tr>
                                <td style="border: 0px;padding-right: 0px;">

                                    <span  class="search_profile_area">
                                        <span class="icon_in_range" id="icon_s_status"><i class="icon-right-dir"></i></span><span class="pfame"><?=$now_profile_name ? $now_profile_name : 'Стандартный профиль'?></span> <span class="icon_in_range" ><i id="del_now_ch_profile" title="Удалить этот профиль" class="icon-cancel-2"></i></span>
                                    </span>
                                    <div class="profiles_container">
                                        <?if($outprofiles):?>
                                        <?foreach($outprofiles as $pval): ?>
                                          <div attrid="<?=$pval['id']?>"><?=$pval['name']?></div>
                                        <?endforeach?>
                                        <?else:?>
                                          <div attrid="">Стандартный профиль</div>
                                        <?endif?>
                                    </div>
                                </td>
                                <td style="border: 0px;padding-left: 5px;">
                                    <span id="newprofile_set" class="new_s_profile"><i title="Новый профиль" class="icon-plus"></i></span>
                                    <a style="font-size: 13px;" class="q_search_link" onmouseover="toolTip('<b>Поисковые профили</b> - это инструмент для отслеживания большого количества лотов по заданным поисковым параметрам.<hr/>Более подробное описание по ссылке:')" onmouseout="toolTip()" href="<?=$home?>/pages/8">Что это?</a>
                                </td>
                                <td style="border: 0px;">

                                </td>
                                <td  style="border: 0px;white-space: nowrap">
                                    <div class="set_button_cont">
                                    <span id="search_in_table" class="urlbutton_index button_no_top_index">Искать</span> &nbsp; <span id="clear_set_table" class="urlbutton_index button_no_top_index">Очистить</span>
                                    </div>
                                </td>
                            </tr>
                        </table>


                </div>

            </div>
        </td>
        <td class="right_back_menu_index">
            <div class="right_panel_conf_index">
                <div class="menu_rt_index">Новости площадок:</div>
                <div class="news_back_right" id="bs_news_container">

                    <div class="news_text" id="bs_news_text">
                        <?if($outnews):?>
                        <?foreach($outnews as $otn): ?>
                        <table class="newsitem_table">
                          <tr>
                              <td style="width: 29px;">
                                  <i class="icon-newspaper"></i>
                              </td>
                              <td>
                                   <?=$otn['text']?><br/>
                                  <span class="undtexttime">Время: <b><?=$otn['time']?></b>, <?=$otn['data']?></span>
                              </td>
                          </tr>
                        </table>
                        <hr class="hrnews"/>
                        <?endforeach?>
                        <?else:?>
                           <div style="text-align: center;margin-top: 10px;color:#b8baba;">Нет новостей</div>
                        <?endif?>
                    </div>
                    <div class="grback"></div>
                    <div class="news_button_cont" id="bs_news_button_cont">
                      <a href="<?=$home?>/pnews" class="urlbutton_index button_no_top_index">Показать все</a>
                    </div>
                </div>

            </div>
        </td>
    </tr>
</table>

<script type="text/javascript">
    function bs_set_block_height()
    {
        // =(
        var set_block_h = $('#bs_set_container').height();
        var button_cont_h = $('#bs_news_button_cont').height();
        var block_h = set_block_h-button_cont_h;
        $('#bs_news_text').css({height: block_h});
        $('#bs_news_text').css({overflow: 'hidden'});
        $('#bs_news_container').css({height: set_block_h});
    }

    $(document).ready(function(){
        bs_set_block_height();
    });

</script>

<div class="table_menu" >
    <table class="table_tab">
        <tr>
            <td><span attr="-2" id="bs_tab_-2" >Все</span></td>

            <?foreach($categories as $ckey=>$cvalue): ?>
                <td><span attr="<?=$ckey?>" id="bs_tab_<?=$ckey?>" ><?=$cvalue?></span></td>
            <?endforeach?>

            <td><span attr="0" id="bs_tab_0" >Прочее</span></td>
            <td class="no_tab_right"><span attr="-1" id="bs_tab_-1" href="<?=$home?>"><i class="icon-star"></i>Избранное</span></td>

        </tr>
    </table>
</div>

<div class="content bs_index_table">
    <div class="contbody" style="padding: 0; border:0;">

        <div id="table_default_set" style="display: none"><?=$table_default_set?></div>
        <div id="table_set" style="display: none"><?=$table_set?></div>

        <div class="top_nav_info">
        <div class="results"></div>
        <div class="info">
            <span class="new"> - Новый лот</span>
            <span class="updated"> - Обновленный лот</span>
            <span class="notupdated"> - Нет изменений</span>
        </div>
        <? if(core::$user_id): ?>
        <?if(core::$rights == 100):?>
        <span class="export">Сохранить избранное в файл</span>
        <? endif; ?>
        </div>
        <script>
            $(function(){
                $('.export').click(function(){
                    $('.export-block').slideToggle();
                });
                
                $('#export').submit(function(e) {
                    e.preventDefault();
                    var action = $(document.activeElement).attr('data-action');
                    
                    $.ajax({
                        type: 'POST',
                        url: '/tabledata/export?action='+action,
                        data: $(this).serialize(),
                        success: function(result){
                            var data = jQuery.parseJSON(result);
                            
                            if(data.status == 0) {
                                create_head_mess('Данная функция доступна на тарифном плане VIP.');
                            } else if (data.status == 1) {
                            
                            } else if (data.status == 2) {
                                window.location = data.file;
                            } else if (data.status == 2) {
                                create_head_mess('Файл отправлен на почту');
                            } else {
                                 create_head_mess('Не предвиденная ошибка');
                            }

                            load_table();
                        }
                    });
                });
                
                $('input[name=all]').click(function(){
                    if($(this).is(':checked')) {
                        $('input[name^="fields"]').each(function(){
                            $(this).prop('checked',true);
                        });
                    } else {
                        $('input[name^="fields"]').each(function(){
                            $(this).removeAttr('checked');
                        });
                    }
                });
                
            });
        </script>
            <div class="export-block">
                <div>
                Отметьте необходимые поля лотов и скачайте файл Excel, так же можно отправить файл на вашу почту, которую вы указали в личном кабинет.
                </div>
                <form method="post" id="export">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <table class="export-table">
                    <tr>
                        <td width="100">
                            <label><input type="checkbox" name="fields[name]" checked>Название</label>
                            <label><input type="checkbox" name="fields[type]" checked>Тип</label>
                            <label><input type="checkbox" name="fields[region]" checked>Регион</label>
                            <label><input type="checkbox" name="fields[category]" checked>Категория</label>
                        </td>
                        <td width="100px">
                            <label><input type="checkbox" name="fields[begin_date]" checked>Дата начала</label>
                            <label><input type="checkbox" name="fields[end_date]" checked>Дата окончания</label>
                            <label><input type="checkbox" name="fields[status]" checked>Статус</label>
                        </td>
                        <td width="50px">
                            <label><input type="checkbox" name="fields[begin_price]" checked>Начальная цена</label>
                            <label><input type="checkbox" name="fields[current_price]" checked>Текущая цена</label>
                            <label><input type="checkbox" name="fields[market_price]" checked>Рыночная цена</label>
                        </td>
                        <td width="50px">
                            <label><input type="checkbox" name="fields[profit_rub]" checked>Доход, руб.</label>
                            <label><input type="checkbox" name="fields[profit_percent]" checked>Доходность, %</label>
                            <label><input type="checkbox" name="fields[drop_price]" checked>Понижение цены, %</label>
                        </td>
                    </tr>
                    <tr>
                        <td width="100">
                            <label><input type="checkbox" name="fields[bankrupt]" checked>Банкрот</label>
                            <label><input type="checkbox" name="fields[case_number]" checked>Дело №</label>
                            <label><input type="checkbox" name="fields[bankrupt_inn]" checked>ИНН банкрота</label>
                        </td>

                        <td width="100px">        
                            <label><input type="checkbox" name="fields[organizer]" checked>Организатор торгов</label>
                            <label><input type="checkbox" name="fields[organizer_inn]" checked>ИНН организатора</label>
                            <label><input type="checkbox" name="fields[arbitrator]" checked>Арбитражный управляющий</label>
                        </td>
                        <td>
                            <label><input type="checkbox" name="fields[contact_person]" checked>Контактное лицо</label>
                            <label><input type="checkbox" name="fields[trades_link]" checked>Торги на площадке</label>
                            <label><input type="checkbox" name="fields[fed_link]" checked>Лот на федресурсе</label>
                        </td>
                        <td class="all">
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td width="100px">
                         <label><input type="checkbox" name="all" checked>Отметить все поля</label>
                            
                        </td>
                        <td colspan="3" class="group_input">
                            <label><input type="checkbox" name="delete">Очистить избранное после сохранения</label>
                            <input type="submit" class="urlbutton_index button_no_top_index" data-action="email" value="Отправить на почту">
                            <input type="submit" class="urlbutton_index button_no_top_index" data-action="download" value="Скачать">
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            <? endif; ?>
        </div>
        <table class="data_table" >
            <tr>
                <th colattr="undefined" style="background:white;border:0px;padding: 40px 0;font-size: 14px;"><i class="icon-spin5"></i> Загрузка...</th>
            </tr>
        </table>

        <div class="bottomp_nav_info">
            <div class="results"></div>
            <div class="info">
                <span class="new"> - Новый лот</span>
                <span class="updated"> - Обновленный лот</span>
                <span class="notupdated"> - Нет изменений</span>
            </div>
        </div>

    </div>
</div>

<div id="navigation_container"></div>

<script type="text/javascript">
    
    var engine_formid = <?=core::$formid?>;
    var engine_settings = jQuery.parseJSON($('#table_set').text());
    var default_settings = jQuery.parseJSON($('#table_default_set').text());
    var engine_global_loader = 0; //Показывать ли в первый раз оповещение о загрузке таблицы

    var engine_home = '<?=$home?>';
    var engine_now_profile = '<?=$now_profile_id?>';

    
    $(document).ready(function()
    {
        restore_settings();
        load_table();
    });

    $(document).on('click', '.data_table .icon_to_click', function(){
        listen_to_favorite(this);
    });

    $(document).on('click', '#search_in_table', function(){
        search_listener();
    });

    $(document).on('click', '#clear_set_table', function(){
        clean_set_listener();
    });

    $(document).on('click', '.data_table tr th', function(){
        columns_sort_listener(this);
    });

    $(document).on('click', '.table_tab td span', function(){
        var id = $(this).attr('attr');
        if(id !== '-1' && $('.export-block').is(":visible")) {
            $('.export-block').slideToggle();
        }
        engine_settings.category = id
        engine_settings.page = 1;
        save_settings_and_load();
    });

    $(document).ready(function(){
        $('[name="begin_set_date"]').jdPicker();
        $('[name="end_set_date"]').jdPicker();
    });


</script>

<div id="popup_overlay_region" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <div class="main_pop_head">
          Выбор регионов
        </div>
        <div class="main_pop_body">
          <span class="action_span" id="region_mark_all"><i class="icon-ok"></i> Отметить все</span><br/>
          <span class="action_span" id="region_delete_all"><i class="icon-cancel"></i> Снять все</span>
        </div>
        <div class="main_pop_body" id="place_table">
            <table width="100%">
                <?$i = 1;?>

                <?foreach($bold_places as $pkey=>$pvalue): ?>
                <?if($i % 2):?><tr><?endif?>
                    <td width="50%">
                        <label><input type="checkbox" <?if($places_used[$pkey] == 1):?> checked="checked" <?endif?> name="place_number_<?=$pkey?>"/> <span style="font-weight: bold;"><?=$pvalue?></span></label><br/>
                    </td>
                    <?if($i % 2):?>
                    <?$last_tr = 0;?>
                    <?else:?>
                    <?$last_tr = 1;?>
                </tr>
                <?endif?>
                <?$i++;?>
                <?endforeach?>

                <?if(!$last_tr):?>
                <td>&nbsp;</td></tr>
                <?endif?>

            </table>

            <hr class="reg_delimiter" />

            <table width="100%">
            <?$i = 1;?>

            <?foreach($places as $pkey=>$pvalue): ?>
                <?if($i % 2):?><tr><?endif?>
                    <td width="50%">
                       <label><input type="checkbox" <?if($places_used[$pkey] == 1):?> checked="checked" <?endif?> name="place_number_<?=$pkey?>"/> <?=$pvalue?></label><br/>
                    </td>
                    <?if($i % 2):?>
                    <?$last_tr = 0;?>
                    <?else:?>
                    <?$last_tr = 1;?>
                </tr>
                <?endif?>
                <?$i++;?>
            <?endforeach?>
            <tr>
             <td>&nbsp;</td>
            <td width="50%" style="font-weight:bold;">
                <label>
                    <input type="checkbox" name="place_number_0" checked>
                    Не определен
                </label>
                <br>
            </td>
            </tr>
            <?if(!$last_tr):?>
            <td>&nbsp;</td>
            </tr>
            <?endif?>
            
            </table>
        </div>
        <div class="main_pop_bottom">
            <span id="region_popup_close" class="urlbutton_index">Сохранить</span>
        </div>
    </div>
</div>

<div id="popup_overlay_platform" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <div class="main_pop_head">
            Выбор площадок
        </div>
        <div class="main_pop_body">
            <span class="action_span" id="platform_mark_all"><i class="icon-ok"></i> Отметить все</span><br/>
            <span class="action_span" id="platform_delete_all"><i class="icon-cancel"></i> Снять все</span>
        </div>
        <div class="main_pop_body" id="platform_table">

            <table width="100%">
                <?$i = 1;?>

                <?foreach($platforms as $pkey=>$pvalue): ?>
                <?if($i % 2):?><tr><?endif?>
                    <td width="50%">
                        <label><input type="checkbox" <?if($platforms_used[$pkey] == 1):?> checked="checked" <?endif?> name="platform_number_<?=$pkey?>"/><i class="icon-globe-table"></i><?=$pvalue?></label><br/>
                    </td>
                    <?if($i % 2):?>
                    <?$last_tr = 0;?>
                    <?else:?>
                    <?$last_tr = 1;?>
                </tr>
                <?endif?>
                <?$i++;?>
                <?endforeach?>

                <?if(!$last_tr):?>
                <td>&nbsp;</td></tr>
                <?endif?>

            </table>

        </div>
        <div class="main_pop_bottom">
            <span id="platform_popup_close" class="urlbutton_index">Сохранить</span>
        </div>
    </div>
</div>

<div id="popup_overlay_newprofile" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <?if(core::$user_id):?>
        <div class="main_pop_head">
            Новый профиль
        </div>
        <div class="main_pop_body">

            <b>Название профиля:</b><br/>
            <div id="error_newprofile_log" style="color:red;font-size: 13px;"></div>
            <input id="name_new_profile" type="text" name="orig_pass" value="" />
            <hr/>
            <i class="icon-attention"></i> Буден создан новый чистый профиль с введенным названием.
            При работе с этим профилем все изменения в поисковых параметрах будут сохранятся в этом поисковом профиле после нажатия кнопки "Искать".
            Очистка полей будет работать только для выбранного профиля.
        </div>
        <div class="main_pop_bottom">
            <span id="newprofile_popup_create" class="urlbutton_index">Создать</span> &nbsp; <span id="newprofile_popup_close" class="urlbutton_index">Отмена</span>
        </div>
        <?else:?>
        <div class="main_pop_head">
            Новый профиль
        </div>
        <div class="main_pop_body">
            <div style="text-align: center;">
                <b>Ошибка!</b> Профили могут создавать только зарегистрированные пользователи!<br/>

            </div>

        </div>
        <div class="main_pop_bottom">
            <a class="urlbutton_index" href="">Вход</a> &nbsp; <a class="urlbutton_index" href="">Регистрация</a>
        </div>
        <?endif?>
    </div>
</div>

<div id="popup_overlay_delprofile" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <?if(core::$user_id):?>
        <div class="main_pop_head">
            Удаление профиля
        </div>
        <?if($now_profile_id != $default_profile_id):?>
             <div class="main_pop_body">
                <div style="text-align: center;">Вы действительно хотите удалить текущий поисковой профиль?</div>
             </div>
             <div class="main_pop_bottom">
                <span id="delprofile_doit" class="urlbutton_index">Удалить</span> &nbsp; <span id="delprofile_popup_close" class="urlbutton_index">Отмена</span>
             </div>
        <?else:?>
            <div class="main_pop_body">
                <div style="text-align: center;">Нельзя удалить стандартный профиль!</div>
            </div>
            <div class="main_pop_bottom">
                <span id="delprofile_popup_close" class="urlbutton_index">Отмена</span>
            </div>
        <?endif?>
        <?else:?>
        <div class="main_pop_head">
            Ошибка
        </div>
        <div class="main_pop_body">
            <div style="text-align: center;">Управлять профилями могут только зарегистрированные пользователи!</div>
        </div>
        <div class="main_pop_bottom">
            <span id="delprofile_popup_close" class="urlbutton_index">Отмена</span>
        </div>
        <?endif?>
    </div>
</div>

<div style="text-align: center; cursor:pointer;">
    <a href="https://vk.com/money_mihalchenco">
        <img src="http://bankrot-spy.ru/themes/web/default/images/banners/banner-bankrot.gif">
    </a>
</div>

<div class="content" style="margin:8px 0 8px 16px;">
    <div class="conthead" style="border-bottom: 1px dotted #E4E4E4;background:#fff; padding: 10px 17px;">
        <table>
            <tr>
                <td>
                    <b><?=$article['name']?></b>
                </td>
                <td class="timetd">
                    <?=$article['time']?>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext"><div class="image_resizer"><?=$article['text']?></div></div>
    <div class="contfintext" style="padding:15px; text-decoration:underline;">
        <a href="/articles/allarticles">Читать все статьи</a>
    </div>
</div>