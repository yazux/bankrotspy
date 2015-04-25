            </td>
        </tr>
    </table>
</div>


            <div class="mainundall"></div>
<div class="prebottom">
    <table>
        <tr>
            <td style="width: 100%;padding-top: 10px;padding-left: 13px;">
               <script async type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div style="display: inline-block; margin-right: 5px;" class="yashare-auto-init" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,gplus" data-yashareTheme="counter" data-yashareImage="<?=$themepath?>/images/apple-touch-icon.png"></div>
               <span style="color: #bababa;position: relative;top: 1px;">BANKROT-SPY.RU <?=date("Y")?>г. | <a href="<?=$home?>/pages/7">Контакты</a></span>
            </td>

            <td style="min-width: 210px;padding-top: 10px;text-align: right;padding-right: 17px;">
                <!--<span style="color: #888888"><?=round((microtime(true) - rem::get('microtime'))*1000, 0)?>ms, sql:<?=$sql_count?></span>-->
                <a class="user_onl_bottom" href="<?=$home?>/user/online"><i class="icon-user-male-bottom"></i> <?=$onl_all?></a>
            </td>
        </tr>
    </table>
</div>
<div class="bottom">
    Информация на данном сайте предоставляется "КАК ЕСТЬ" и предназначена только для ознакомительных целей без каких-либо гарантий и передачи прав. Любое использование информации в иных целях запрещено и производится на ваш страх и риск. Все права защищены.
</div>


<script type="text/javascript">

    //Эти 3 строки ничего страшного не делают
    //Нужно просто для системы защиты
    $(document).ready(function() {
        setInterval('connection_keeper()',30000);
    });

    function set_normal_height()
    {
        if(device.mobile() == false)
        {
          var height = $(window).height();
          var prebottom_h = $('div.prebottom').height();
          var bottom_h = $('div.bottom').height();
          var allhead_h = $('table.allhead').height();
          height = height - bottom_h - prebottom_h - allhead_h - 28;
          $('td.main').height(height);
        }
    }

    set_normal_height();

    $(window).resize(function(event) {
      set_normal_height();
      correct_images();
    });

    //учитываем скроллбар
    if(!get_scroll('Height') && ($('body').width() - $('.all_content').width() > 20)) {
        $('body').css({'margin-right' : scrollWidth()});
    }
</script>


</div>
</div>
</body>
</html>