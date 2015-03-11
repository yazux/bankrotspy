<table>
    <tr>
        <td class="right_back_menu_index_set">
            <div class="right_panel_conf">
                <div class="menu_rt_index">Настройка:</div>
                <div class="news_back" id="bs_set_container">

                    <br/><br/><br/><br/><br/><br/><br/><br/>

                </div>

            </div>
        </td>
        <td class="right_back_menu_index">
            <div class="right_panel_conf">
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





