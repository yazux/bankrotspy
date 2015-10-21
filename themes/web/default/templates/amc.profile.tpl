<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> <?= $title ?></h2></div>
                <div class="contbody_forms">
                    <table class="lottable">
                        <tr>
                            <td width="200"><b>Арбитражный управляющий (АУ):</b></td>
                            <td><?= str_replace(array('ИП ', 'ИП'), '', $data['manager']) ?></td>
                        </tr>
                        <tr>
                            <td width="200"><b>Контактное лицо:</b></td>
                            <td><?= !empty($data['contact_person']) ? $data['contact_person'] : 'Нет'; ?></td>
                        </tr>
                        <tr>
                            <td width="200"><b>Телефон:</b></td>
                            <td><?= !empty($data['phone']) ? intval($data['phone']) : 'Нет'; ?></td>
                        </tr>
                        <tr>
                            <td width="200"><b>ИНН:</b></td>
                            <td><?= $data['inn'] ?></td>
                        </tr>
                        <tr>
                            <td width="200"><b>Рейтинг:</b></td>
                            <td>
                                <? if($data['totaldoc'] < 3 && $data['totaldoc'] > 0 ): ?>
                                    <? $rating = 'Мало данных'; ?>
                                <? elseif($data['totaldoc'] == 0): ?>
                                    <? $rating = 'Нет данных'; ?>
                                <? elseif($data['bal'] > 5): ?>
                                    <? 
                                        $rating = '<span class="plus">' . $data['bal'] . '</span>';
                                    ?>
                                <? elseif($data['bal'] <= 5): ?>
                                    <?
                                        $rating = '<span  class="minus">' . $data['bal'] . '</span>';
                                    ?>
                                <? endif; ?>
                                <?= $rating ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="200"><b>Документы судов:</b></td>
                            <td>
                            <? if(!empty($data['totaldoc'])): ?>
                                <a href="<?= $data['linkdocs'] ?>" target="_blank">Смотреть</a>
                            <? else: ?>
                                Нет данных
                            <? endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="200"><b>Документы ФАС:</b></td>
                            <td>
                            <? if(!empty($data['fasdocs'])): ?>
                                <a href="<?= $data['fasdocs'] ?>" target="_blank">Смотреть</a>
                            <? else: ?>
                                Нет данных
                            <? endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="200"><b>Профиль на федресурсе:</b></td>
                            <td>
                            <? if(!empty($data['arbitr_profile'])): ?>
                                <? $link = '<a href="' . $data['arbitr_profile'] . '" target="_blank">Смотреть</a>' ?>
                            <? elseif(!empty($data['org_profile'])): ?>
                                <? $link = '<a href="' . $data['arbitr_profile'] . '" target="_blank">Смотреть</a>' ?>
                            <? else: ?>
                                <? $link = 'Нет'; ?>
                            <? endif; ?>
                            <?= $link ?>
                            </td>
                        </tr>
                    </table>
                
                    <div id="myChart"></div>
                </div>
            </div>
        </td>
    </tr>
</table>
<? if(!empty($data['chart']) && $data['totaldoc'] > 3): ?>

<script type="text/javascript" src="http://www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/themes/light.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/lang/ru.js"></script>
<style>

#myChart {
z-index:999999;
background:#fff;
display:block;

width:100%;
height:500px;
}
</style>

<script type="text/javascript">
var chart = AmCharts.makeChart("myChart", {
    "language": "ru",
    "type": "serial",
    "theme": "light",
    "titles": [{
        "text": "Динамика рейтинга АУ",
        "size": 15
    }],
    "marginRight": 20,
    "autoMarginOffset": 20,
    "dataDateFormat": "YYYY-MM-DD",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left"
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
        "id": "g1",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
        "valueField": "value",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]]</span><br>[[value]]</div>"
    }],
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":0,
        "valueLineAlpha":0.2
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    },
    "dataProvider": [{
        "date": "2015-09-08",
        "value": 0
    },
    <? foreach($data['chart'] as $rating): ?>
    {
        "date": "<?= $rating['date'] ?>",
        "value": <?= $rating['value'] ?>
    },
    <? endforeach; ?>
    ]});
    
    chart.addListener("rendered", zoomChart);
    zoomChart();
    function zoomChart() {
        chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
    }
</script>
<? endif; ?>