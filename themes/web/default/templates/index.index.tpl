<script type="text/javascript">
    
    function simple(){
        $(".right_panel_conf").css('width', '868px');
        $(".right_back_menu_index").css('display', 'block');
        $("#extend_td").css('display', 'none');
        $("#simple").css('font-weight', 'bold');
        $("#extend").css('font-weight', 'normal');
        $("#search_form_extend").val('0');
    }

    function extend(){
        $(".right_panel_conf").css('width', '1300px');        
        $(".right_back_menu_index").css('display', 'none');
        $("#extend_td").css('display', 'block');
        $("#extend_td").css('width', '320px;');
        $("#simple").css('font-weight', 'normal');
        $("#extend").css('font-weight', 'bold');
        $("#extend").css('font-weight', 'bold');
        $("#search_form_extend").val('1');
    }
</script>
<form id="search_form">
    
<input type="hidden" name="search_form_extend" id="search_form_extend" value="0">

<? if ( $main_adv_text && !$user_id ) : ?>
<div class="adv-text-wrap">
    <div class="adv-text" style="margin-bottom: 10px; text-align: justify;">
        <div style="margin: 0px 20px; padding: 10px 0px; text-align: justify;"><?=$main_adv_text?></div>
    </div>
</div>
<? endif ?>

<table>
    <tr>
        <td class="right_back_menu_index_set">
            <span id="on_load_new_page"></span>
            
            <div class="right_panel_conf">
                <div class="menu_rt_index">
                    Настройки поиска:
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" onclick="simple();" id="simple" style="font-weight: bold;">Простой</a> /
                    <a href="#" onclick="extend();" id="extend" style="font-weight: normal;">Расширенный</a>
                </div>
                <div class="news_back" id="bs_set_container">
                    <table>
                        <tr>
                            <td valign="top">
                                <i class="icon-location"></i> <b>Регионы:</b> выбрано <span id="total_places_set"><?=count($places_used)?></span> из <?=count($places_def)?> <span id="region_set" class="change_block_set" style="padding-right: 200px; border-right: 1px solid #e9e9e9;"><i class="icon-edit-orange"></i>Изменить</span>
                                <i style="margin-left: 10px;" class="icon-globe-set"></i> <b>Площадки:</b> выбрано <span id="total_platforms_set"><?=count($platforms_used)?></span> из <?=count($platforms_def)?> <span id="platform_set" class="change_block_set"><i class="icon-edit-orange"></i>Изменить</span>
                            </td>
                        </tr>
                    </table>

                    <hr class="super_hr"/>
                    
                    <table>
                        <tr>
                           <td valign="top">
                               <i class="icon-search"></i> Поиск по названию: 
                               <a style="font-size: 13px; margin-left: 60px;" href="http://bankrot-spy.ru/articles/post35">Как искать?</a>
                               <input type="text" name="svalname" id="svalname" style="width: 95%;"/>

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
                           <td valign="top" style="position: relative;">
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
                               
                                <div style="position: absolute; left: 10px; top: 100px; width: 260px;">
                                    <input style="width: 37px;" onmouseover="toolTip('Число или интервал,<br/> например: 2-5<hr/>Нельзя одновременно использовать Дату подачи и эту функцию.')" onmouseout="toolTip()" type="text" name="altintconf"/>
                                    <span style="font-size: 13px">Дней до подачи</span>
                                    <input type="checkbox" name="hide" value="1" style="margin-left: 10px;"/> <span style="font-size: 13px">Мусор</span>
                                </div>
                                <div style="position: absolute; left: 10px; top: 135px; width: 260px;">
                                    <input type="checkbox" name="new_lots" <?= !empty($new_lots) ? 'checked' : '' ?>/><span style="font-size: 13px">Новые лоты</span>
                                    <input type="checkbox" name="more" value="1" style="margin-left: 10px;"/> <span style="font-size: 13px">Подробнее</span>
                                    <input type="checkbox" name="hasPhoto" value="1" style="margin-left: 10px;"/> <span style="font-size: 13px">Фото</span>
                               </div>
                           </td>
                           
                           <td valign="top" id="extend_td" style="display: none; width: 320px;">
                               <i class="icon-search"></i> Дополнительные поля:<br/>
                               <table class="nomarginnews">
                                   <tr>
                                       <td style="width: 90px;">Банкрот: </td><td style="white-space: nowrap;"><input type="text" name="inn"/></td>
                                   </tr>
                                   <tr>
                                       <td style="width: 90px;">Арбитр. управ. и Организатор: </td><td style="white-space: nowrap;"><input type="text" name="au"/></td>
                                   </tr>
                                   <tr>
                                       <td style="width: 90px;">Номер дела: </td><td style="white-space: nowrap;"><input type="text" name="case_number"/></td>
                                   </tr>
                                   <tr>
                                       <td style="width: 90px;">Номер торгов: </td><td style="white-space: nowrap;"><input type="text" name="trade_number"/></td>
                                   </tr>
                               </table>
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
                                <td style="border: 0px; padding-right: 0px; width: 300px; vertical-align: middle;">

                                    <span style="float:left;" class="search_profile_area">
                                        <span class="icon_in_range" id="icon_s_status"><i class="icon-right-dir"></i></span>
                                        <span class="pfame"><?=$now_profile_name ? $now_profile_name : 'Стандартный профиль'?></span> <span class="icon_in_range" ><i id="del_now_ch_profile" title="Удалить этот профиль" class="icon-cancel-2"></i></span>
                                    </span>
                                    <div style="float:left;" class="profiles_container">
                                        <?if($outprofiles):?>
                                        <?foreach($outprofiles as $pval): ?>
                                          <div attrid="<?=$pval['id']?>"><?=$pval['name']?></div>
                                        <?endforeach?>
                                        <?else:?>
                                          <div attrid="">Стандартный профиль</div>
                                        <?endif?>
                                    </div>
                                    <div style="float: left; margin-top: 4px; width: 100px;">
                                        <span id="newprofile_set" class="new_s_profile"><i title="Новый профиль" class="icon-plus"></i></span>
                                        <a style="font-size: 13px;" class="q_search_link" onmouseover="toolTip('<b>Поисковые профили</b> - это инструмент для отслеживания большого количества лотов по заданным поисковым параметрам.<hr/>Более подробное описание по ссылке:')" onmouseout="toolTip()" href="<?=$home?>/pages/8">Что это?</a>
                                    </div>
                                </td>
                                <!--td style="width: 100px; border: 0px;padding-left: 5px;">
                                    <span id="newprofile_set" class="new_s_profile"><i title="Новый профиль" class="icon-plus"></i></span>
                                    <a style="font-size: 13px;" class="q_search_link" onmouseover="toolTip('<b>Поисковые профили</b> - это инструмент для отслеживания большого количества лотов по заданным поисковым параметрам.<hr/>Более подробное описание по ссылке:')" onmouseout="toolTip()" href="<?=$home?>/pages/8">Что это?</a>
                                </td-->
                                
                                
                                <style>
                                    .search_type_style{
                                        margin-top: 5px;
                                        padding-top: 4px;
                                        text-align: center;
                                        width: 130px;
                                        height: 28px;
                                        color: #4a4a4a;
                                        font-size: 13px;
                                    }
                                </style>
                                
                                <td  style="width: 400px; border: 0px; white-space: nowrap">
                                    <div class="set_button_cont">
                                        <select name="search_type" id="search_type" class="search_type_style">
                                            <option value="any">Любое из слов</option>
                                            <!--option value="all">Все слова</option-->
                                            <option value="phrase">Точная фраза</option>
                                      </select>
                                        &nbsp; 
                                        <span id="search_in_table" class="urlbutton_index button_no_top_index">Искать</span> 
                                        &nbsp; 
                                        <span id="clear_set_table" class="urlbutton_index button_no_top_index">Очистить</span>
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

</form>

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
        <span class="export"><i class="fa fa-long-arrow-down"></i> Сохранить избранное в файл</span>
        </div>
        <script>
            $(document).ready(function(){
                $('.export').click(function(){
                    var arrow = $(this).find('i');
                    $('.export-block').slideToggle(function() {
                        if($(this).is(':visible')) {
                            $(arrow).removeClass('fa-long-arrow-down').addClass('fa-long-arrow-up');
                        } else {
                            $(arrow).removeClass('fa-long-arrow-up').addClass('fa-long-arrow-down');
                        }
                    });
                });
                
                $('#export').submit(function(e) {
                    e.preventDefault();
                    var action = $(document.activeElement).attr('data-action');
                    
                    $.ajax({
                        type: 'POST',
                        url: '/tabledata/export?action='+action,
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 0 || data.status == 1 || data.status == 3) {
                                create_notify(data.message);
                                $('.export-block').slideToggle();
                            } else if (data.status == 2) {
                                window.location = data.file;
                            } else {
                                 create_notify('Произошла ошибка!');
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

    $(document).ready(function() {
        // Запретить сабмит формы
        $("#search_form").submit(function(){return false;});
        
        restore_settings();
        load_table();
        
        // Какую форму показывать, расширенную или сокращенную
        var sfe = $('#search_form_extend').val();
        if ( sfe == 1 ) {
            extend();
        } else {
            simple();
        }
        
        $('[name="begin_set_date"]').jdPicker();
        $('[name="end_set_date"]').jdPicker();
        
        // Если меняется значение лотов на страницу, то повторить поиск
        $(document).on('change', '#kmess', function(){
            //engine_settings.kmess = $('#kmess option:selected').val();
            search_listener();
        });
    });

    $(document).on('click', '.data_table .icon_to_click_fav', function(){
        var tab = $('.active_tab').attr('attr');
        if(tab == '-1') {
            listen_to_favorite(this, true);
        } else {
            listen_to_favorite(this, false);
        }
    });
    
    $(document).on('click', '.data_table .icon_to_click_hide', function(){
        var tab = $('.active_tab').attr('hide_attr');
        if(tab == '-1') {
            listen_to_hide(this, true);
        } else {
            listen_to_hide(this, true);
        }
    });
    
    $(document).on('click', '#all_favorite', function(){
        var now_class = $('#all_favorite').attr('class');
        var action = 'add';
        var ids = new Array();
        var i;
        
        if(now_class == 'icon-star-clicked') {
            //Кнопка уже нажата
            $('.icon_to_click_fav').each(function() {
                $(this).find('.icon-star-clicked').attr('class', 'icon-star-empty');
                $(this).find('.icon-star-clicked').attr('title', 'Добавить лоты в избранное');
                i = $(this).attr('attr');
                ids.push(i);
                //console.log(i);
                //ids.push(i);
            });
            action = 'delete';
            
            $('#all_favorite').attr('class', 'icon-star-empty');
            $('#all_favorite').attr('title', 'Добавить все лоты в избранное');
        } else {
            //Кнопка не нажата
            $('.icon_to_click_fav').each(function() {
                $(this).find('.icon-star-empty').attr('class', 'icon-star-clicked');
                $(this).find('.icon-star-empty').attr('title', 'Удалить лоты из избранного');
                i = $(this).attr('attr');
                ids.push(i);
            });
            //console.log(ids);
            action = 'add';
          
            $('#all_favorite').attr('class', 'icon-star-clicked');
            $('#all_favorite').attr('title', 'Удалить все лоты из избранного');
        }
        
        //console.log(action);
        
        $.ajax({
            method: 'GET',
            url: '/tabledata/favoritemass?action='+action+'&ids='+ids,
            dataType: 'json',
            //data: 'act=add',
            success: function(result){
                create_notify(result.message);
            }
        });
    });
    
    $('#note_window .fa-times').on('click', function(){
        $(this).parent().parent().fadeOut();
    });
    $(document).on('mouseover', '#note', function(){
        var tab = $('.active_tab').attr('attr');
        if(tab != '-1') {
            var text = $(this).attr('data-note');
            toolTip(text);
        }
    });
    
    $(document).on('mouseout', '#note', function(){
        toolTip();
    });
    
    $('#note_window .fa-floppy-o').on('click', function(){
        var id = $(this).parent().find('input[name=id]').val();
        var data = $(this).parent().serialize();
        var text = $(this).parent().find('textarea').val();
        if(text.length > 0) {
            var action = 'save';
        } else {
            var action = 'delete';
        }
        
        $.ajax({
            method: 'POST',
            url: '/tabledata/note?action='+action,
            dataType: 'json',
            data: data,
            success: function(result){
                if (result.error == 1) {
                    create_notify(result.message);
                } else {
                    if (action == 'save') {
                        $(document).find('[data-id='+id+']').children('i').removeClass('fa-sticky-note-o').addClass('fa-sticky-note');
                    } else {
                        $(document).find('[data-id='+id+']').children('i').removeClass('fa-sticky-note').addClass('fa-sticky-note-o');
                    }
                    create_notify(result.message);
                }
            }
        });
        $(document).find('[data-id='+id+']').attr('data-note', text);
        
        $(this).parent().parent().fadeOut();
    });
    /*
    $('#note_window .fa-trash').on('click', function(){
        var id = $(this).parent().find('input[name=id]').val();
        var data = $(this).parent().serialize();
        var text = $(this).parent().find('textarea').val();
        $.ajax({
            method: 'POST',
            url: '/tabledata/note?action=delete',
            dataType: 'json',
            data: data,
            success: function(result){
               
            }
        });
        $(document).find('[data-id='+id+']').attr('data-note', '');
        $(document).find('[data-id='+id+']').children('i').removeClass('fa-sticky-note').addClass('fa-sticky-note-o');
        $(this).parent().parent().fadeOut();
        
    });*/
    
    $(document).on('click', '.data_table #note', function(){
        var tab = $('.active_tab').attr('attr');
        if(tab == '-1') {
            var note = $(this).attr('data-note');
            var top = $(this).offset().top;
            var left = $(this).offset().left;
        
            $('#note_window').css('top', top - 90);
            $('#note_window').css('left', left);
        
            $('#note_window').find('textarea').val(note)
            $('#note_window').find('input[name=id]').val($(this).attr('data-id'));
            $('#note_window').fadeIn();
        }
    });
    
    $(document).keypress(function(e) {
        if(e.which == 13) {
            search_listener();
        }
    });
    
    $(document).on('click', '#search_in_table', function(){
        search_listener();
    });
    
    $(document).on('click', '#clear_set_table', function(){
        //simple();
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

</script>

<div id="popup_overlay_region" class="popup_overlay">
    <div class="popup_table">
        <div class="close_modal_img"><span id="icon_close_butt"><i class="icon-cancel-circled"></i></span></div>
        <div class="main_pop_head">
          Выбор регионов
        </div>
        <div class="main_pop_body">
          <span class="action_span" id="region_mark_all"><i class="icon-ok"></i>Отметить все</span>
          <span class="action_span" id="region_delete_all"><i class="icon-cancel"></i>Снять все</span>
        </div>
        <div class="main_pop_body" id="place_table">
            <table width="100%">
                <? $i = 1; ?>
                <? foreach($regions_top as $pkey=>$pvalue): ?>
                <? if($i % 2): ?> <tr> <?endif?>
                    <td width="50%">
                        <label><input type="checkbox" <?if($places_used[$pkey] == 1):?> checked="checked" <?endif?> name="regions[<?=$pkey?>]"/> <span style="font-weight: bold;color:#4a4a4a;"><?=$pvalue?></span></label><br/>
                    </td>
                    <?if($i % 2): ?>
                    <? $last_tr = 0; ?>
                    <? else: ?>
                    <? $last_tr = 1; ?>
                </tr>
                <? endif; ?>
                <? $i++; ?>
                <? endforeach; ?>

                <? if(!$last_tr): ?>
                <td>&nbsp;</td></tr>
                <? endif ?>

            </table>

            <hr class="reg_delimiter" />
            <script>
                $(function(){
                   
                    $('[data-region]').on('click', function(){
                        var regions = $(this).parent().parent().find('input[name^="regions"]');
                        if($(this).is(':checked')) {
                            $(regions).each(function(){
                                $(this).prop('checked',true);
                            });
                        } else {
                            $(regions).each(function(){
                                $(this).prop('checked', false);
                            });
                        }
                    });
                    
                    $('[data-group]').on('click', function(){
                        var id = $(this).attr('data-group');
                        
                        if(!$(this).is(':checked')) {
                           $('[data-region="'+id+'"]').prop('checked', false);
                        }
                    })
                });
            </script>
            <table width="100%">
            <?
                $i = 0;
                $half = ceil(count($regions_result)/2);
            ?>
                <tr>
                    <td width="50%" valign="top">
                    <? foreach($regions_result as $root_id => $data): ?>
                    <? if($half == $i): ?>
                    </td>
                    <td width="50%" valign="top">
                    <? endif; ?>
                        <div style="padding:3px 0;">
                        <? 
                            $total = count($data['sub']);
                            $selected = 0;
                            foreach($data['sub'] as $key => $region) {
                                if($places_used[$key] == 1) {
                                    $selected++;
                                }
                            }
                            $checked = '';
                            if($total === $selected) $checked = 'checked="checked"';
                        
                        ?>
                        <label style="font-weight:bold;color:#4a4a4a;">
                        <input type="checkbox" <?= $checked ?> data-region="<?= $root_id ?>" />
                        <?= $data['name'] ?>
                        </label>
                        <table>
                        <? foreach($data['sub'] as $key => $region): ?>
                            <tr>
                                <td style="padding-left:20px;">
                                <label>
                                <input type="checkbox" data-group="<?= $root_id ?>" <?= ($places_used[$key] == 1) ? 'checked="checked"' : '' ?> name="regions[<?= $key ?>]" />
                                <?= $region ?>
                                </label>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        </table>
                    <? $i++ ?>
                        </div>
                    <?endforeach?>
                    </td>
                </tr>
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
            <span class="action_span" id="platform_mark_all"><i class="icon-ok"></i>Отметить все</span>
            <span class="action_span" id="platform_delete_all"><i class="icon-cancel"></i>Снять все</span>
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
</div>

<div style="width: 100%; text-align: center;">
    <a href="http://torgi-bankrotov.com/webinar/?index.php?utm_source=spyru" target="_blank">
        <img src="/themes/web/default/images/banners/adv-banner.gif">
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