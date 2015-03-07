            </td>
        </tr>
    </table>
</div>


            <div class="mainundall"></div>
<div class="prebottom">
    <table>
        <tr>
            <td style="width: 100%;padding-top: 6px;">

            </td>
            <td style="min-width: 210px;padding-top: 6px;">

            </td>
        </tr>
    </table>
</div>
<div class="bottom">&nbsp; <span style="color: #888888"><?=round((microtime(true) - rem::get('microtime'))*1000, 0)?>ms | sql:<?=$sql_count?></span>
</div>


<script type="text/javascript">

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