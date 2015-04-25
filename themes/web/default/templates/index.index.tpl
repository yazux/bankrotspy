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
                               <span style="font-size: 13px">Или дней до подачи заявок*:</span>
                               <input style="width: 37px;" onmouseover="toolTip('Число или интервал,<br/> например: 2-5<hr/>Нельзя одновременно использовать Дату подачи и эту функцию.')" onmouseout="toolTip()" type="text" name="altintconf"/><br/>
                           </td>
                           <td valign="top" style="border-right: 0">
                               <i class="icon-rouble"></i> Цена лота:<br/>
                               <table class="nomarginnews">
                                   <tr>
                                       <td style="width: 28px;">С: </td><td><input type="text" name="price_start"/></td>
                                   </tr>
                                   <tr>
                                       <td>По: </td><td><input type="text" name="price_end"/></td>
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
                        <table>
                            <tr>
                                <td style="border: 0px;font-size: 15px;">  </td>
                                <td  style="border: 0px;">
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
                <div class="news_back" id="bs_news_container">

                    <div class="news_text" id="bs_news_text">


                    </div>
                    <div class="grback"></div>
                    <div class="news_button_cont" id="bs_news_button_cont">
                      <a href="" class="urlbutton_index button_no_top_index">Показать все</a>
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
    <div class="contbody" style="padding: 0px">

        <div id="table_default_set" style="display: none"><?=$table_default_set?></div>
        <div id="table_set" style="display: none"><?=$table_set?></div>

        <div class="top_nav_info"></div>

        <table class="data_table" >

        </table>

        <div class="bottomp_nav_info"></div>

    </div>
</div>

<div id="navigation_container"></div>

<script type="text/javascript">
    var engine_formid = <?=core::$formid?>;
    var engine_settings = jQuery.parseJSON($('#table_set').text());
    var default_settings = jQuery.parseJSON($('#table_default_set').text());
    var engine_global_loader = 0; //Показывать ли в первый раз оповещение о загрузке таблицы


    load_table();
    $(document).ready(function()
    {
        restore_settings();
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
        engine_settings.category = $(this).attr('attr');
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

            <?if(!$last_tr):?>
            <td>&nbsp;</td></tr>
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