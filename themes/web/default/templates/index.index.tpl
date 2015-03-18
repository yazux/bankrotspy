<table>
    <tr>
        <td class="right_back_menu_index_set">
            <span id="on_load_new_page"></span>
            <div class="right_panel_conf">
                <div class="menu_rt_index">Настройка:</div>
                <div class="news_back" id="bs_set_container">

                    <table>
                        <tr>
                           <td>
                               &nbsp;<br/><br/><br/><br/><br/><br/><br/>
                           </td>
                           <td>
                               &nbsp;<br/><br/><br/><br/><br/><br/><br/>
                           </td>
                           <td style="border-right: 0">
                               &nbsp;<br/><br/><br/><br/><br/><br/><br/>
                           </td>
                        </tr>
                    </table>
                     <hr class="news_back_hr" />
                    <div class="set_button_cont">
                       <a href="" class="urlbutton_index button_no_top_index">Искать</a> &nbsp; <a href="" class="urlbutton_index button_no_top_index">Очистить</a>
                    </div>
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
            <td><span attr="1" id="bs_tab_1" >Авто</span></td>
            <td><span attr="2" id="bs_tab_2" >Деб. задолженность</span></td>
            <td><span attr="3" id="bs_tab_3" >Зем. участки</span></td>
            <td><span attr="4" id="bs_tab_4" >Сельхоз. имущество</span></td>
            <td><span attr="5" id="bs_tab_5" >Недвиж. жилая</span></td>
            <td><span attr="6" id="bs_tab_6" >Недвиж. коммер.</span></td>
            <td><span attr="7" id="bs_tab_7" >Спецтехника</span></td>
            <td><span attr="8" id="bs_tab_8" >Обор. Инст. Мат.</span></td>
            <td><span attr="9" id="bs_tab_9" >Ценные бумаги</span></td>
            <td><span attr="0" id="bs_tab_0" >Прочее</span></td>

            <td class="no_tab_right"><span attr="-1" id="bs_tab_-1" href="<?=$home?>"><i class="icon-star"></i>Избранное</span></td>

        </tr>
    </table>
</div>

<div class="content bs_index_table">
    <div class="contbody" style="padding: 0px">

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
    var engine_global_loader = 0;

    load_table();

    $(document).on('click', '.data_table .icon_to_click', function(){
        listen_to_favorite(this);
    });

    $(document).on('click', '.table_tab td span', function(){
        engine_settings.category = $(this).attr('attr');
        engine_settings.page = 1;
        save_settings_and_load();
    });
</script>



