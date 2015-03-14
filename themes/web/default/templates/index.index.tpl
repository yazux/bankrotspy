<table>
    <tr>
        <td class="right_back_menu_index_set">
            <div class="right_panel_conf">
                <div class="menu_rt_index">Настройка:</div>
                <div class="news_back" id="bs_set_container">

                    <br/><br/><br/><br/><br/><br/><br/>

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
                      <a href="" class="urlbutton_index button_no_top">Показать все</a>
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

<div class="table_menu">
    <table class="table_tab">
        <tr>
            <td><span id="bs_tab_1" class="active_tab" href="<?=$home?>">Авто</span></td>
            <td><span id="bs_tab_2" href="<?=$home?>">Деб. задолженность</span></td>
            <td><span id="bs_tab_3" href="<?=$home?>">Зем. участки</span></td>
            <td><span id="bs_tab_4" href="<?=$home?>">Сельхоз. имущество</span></td>
            <td><span id="bs_tab_5" href="<?=$home?>">Недвиж. жилая</span></td>
            <td><span id="bs_tab_6" href="<?=$home?>">Недвиж. коммер.</span></td>
            <td><span id="bs_tab_7" href="<?=$home?>">Спецтехника</span></td>
            <td><span id="bs_tab_8" href="<?=$home?>">Обор. Инст. Мат.</span></td>
            <td><span id="bs_tab_9" href="<?=$home?>">Ценные бумаги</span></td>
            <td><span id="bs_tab_10" href="<?=$home?>">Прочее</span></td>

            <td class="no_tab_right"><span id="bs_tab_11" href="<?=$home?>"><i class="icon-star"></i>Избранное</span></td>
        </tr>
    </table>
</div>

<div class="content bs_index_table">
    <div class="contbody" style="padding: 0px">



    <table class="data_table" >

    </table>

        <script type="text/javascript">
            load_table();
        </script>

    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.data_table tr.data_line', function(){
        var rerirect_to = $(this).find('.link_content').attr('attr');
        window.open('<?=$home?>/card/' + rerirect_to);
    });


    $(document).on('click', '.table_tab td span', function(){
        $('.table_tab td span').removeClass("active_tab");
        $(this).addClass("active_tab");
    });
</script>



