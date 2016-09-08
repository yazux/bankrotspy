<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="fa fa-list-alt"></i> <?= $title ?></h2></div>

                <? if(!empty($textData)): ?>
                <div class="contbody_forms">
                    <?= $textData ?>
                </div>
                <? endif; ?>
                <div class="stats_wrapper">
                    <div class="stats_item">
                        <p>Статистика по категориям</p>
                        <canvas id="catChart" width="700" height="400" class="chart"></canvas>
                    </div>
                    <div class="stats_item">
                        <p>Статистика по цене</p>
                        <canvas id="priceChart" width="500" height="400" class="chart"></canvas>
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="stats_wrapper">
                    <div class="stats_item">
                        <p>Статистика по доходности</p>
                        <canvas id="benefitChart" width="450" height="400" class="chart"></canvas>
                    </div>
                    <div class="circle_stats" class="stats_item" >
                        <p>Статистика по типу торгов</p>
                        <canvas width="375" height="400" class="chart"></canvas>
                    </div>
                    <div class="circle_stats" class="stats_item">
                        <p>Статистика по продолжительности торгов</p>
                        <canvas width="375" height="400" class="chart"></canvas>
                    </div>
                </div>
                <div class="stats_wrapper">
                    <div class="stats_item">
                        <p>Статистика по регионам</p>
                        <canvas id="cityChart" width="1200" height="400" class="chart"></canvas>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>

<style>
.fa-list-alt {
    color:#838488;
}

.table th {
    background-color: #ebebeb;
    border: 1px solid #d1d1d1;
    color: #676767;
    font-size: 14px;
    font-weight: normal;
    padding: 4px 16px 4px 3px;
}

.table td{
    border:1px solid #e1e1e1;
    padding:5px 10px;
}
.table tr:hover {
    background:#f8f8f8;
}

.stats_wrapper{
    overflow: auto;
    margin-bottom: 20px;
}

.stats_item{
    height: 430px;
    float: left;
    margin-bottom: 20px;
    text-align: center;
}

.circle_stats{
    height: 400px;
    width: 375px;
    float: left;
    margin-bottom: 20px;
    text-align: center;
}

.stats_item p, .circle_stats p{
    color: #3d3d3d;
    font: 16px 'PT Sans',Arial,Verdana,sans-serif;
    font-weight: bold;
    margin: 5px 0 5px 0;
}

</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
<script>
var data_stats = <?= $datajs ?>;
var names_stats = <?= $sortNamesjs ?>;

var data_graph = [];
// категории
var data_cat_graph = {
    labels: names_stats[0],
    datasets: [
        {
            label: "Статистика по категориям",
            backgroundColor: [
                'rgba(125, 125, 125, 0.5)',
                'rgba(168, 42, 42, 0.5)',
                'rgba(168, 42, 120, 0.5)',
                'rgba(105, 42, 168, 0.5)',
                'rgba(42, 53, 168, 0.5)',
                'rgba(42, 135, 168, 0.5)',
                'rgba(42, 168, 139, 0.5)',
                'rgba(42, 168, 90, 0.5)',
                'rgba(135, 168, 42, 0.5)',
                'rgba(215, 230, 7, 0.5)',
                'rgba(230, 126, 7, 0.5)',
                'rgba(110, 64, 12, 0.5)'
            ],
            borderColor: [
                'rgba(125, 125, 125, 1)',
                'rgba(168, 42, 42, 1)',
                'rgba(168, 42, 120, 1)',
                'rgba(105, 42, 168, 1)',
                'rgba(42, 53, 168, 1)',
                'rgba(42, 135, 168, 1)',
                'rgba(42, 168, 139, 1)',
                'rgba(42, 168, 90, 1)',
                'rgba(135, 168, 42, 1)',
                'rgba(215, 230, 7, 1)',
                'rgba(230, 126, 7, 1)',
                'rgba(110, 64, 12, 1)'
            ],
            borderWidth: 1,
            data: data_stats[0],
        }
    ]
};
var ctx = document.getElementById('catChart');
var catChart = new Chart(ctx,{
    type: 'bar',
    data: data_cat_graph,
    options: {
        legend: {
            display: false
        }
    }
});

// цены
var data_price_graph = {
    labels: names_stats[1],
    datasets: [
        {
            label: "Статистика по ценам",
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            data: data_stats[1],
        }
    ]
};
var ctx = document.getElementById('priceChart');
var priceChart = new Chart(ctx,{
    type: 'bar',
    data: data_price_graph,
    options: {
        legend: {
            display: false
        }
    }
});


// доходность
var data_benefit_graph = {
    labels: names_stats[2],
    datasets: [
        {
            label: "Статистика по доходности",
            backgroundColor: [
                'rgba(0, 255, 0, 0.5)',
                'rgba(168, 255, 0, 0.5)',
                'rgba(255, 255, 168, 0.5)',
                'rgba(255, 0, 168, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(0, 255, 0, 1)',
                'rgba(168, 255, 0, 1)',
                'rgba(255, 255, 168, 1)',
                'rgba(255, 0, 168, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            data: data_stats[2],
        }
    ]
};
var ctx = document.getElementById('benefitChart');
var benefitChart = new Chart(ctx,{
    type: 'bar',
    data: data_benefit_graph,
    options: {
        legend: {
            display: false
        }
    }
});

for (var i = 2; i < data_stats.length; i++) {
    data_graph[i] = {
        labels: names_stats[i],
        datasets: [
            {
                data: data_stats[i],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.75)',
                    'rgba(54, 162, 235, 0.75)',
                    'rgba(255, 206, 86, 0.75)',
                    'rgba(102, 204, 102, 0.75)',
                    'rgba(153, 102, 255, 0.75)',
                    'rgba(255, 159, 64, 0.75)'
                    
                ],
                hoverBackgroundColor: [
                    'rgba(255, 99, 132, 0.9)',
                    'rgba(54, 162, 235, 0.9)',
                    'rgba(255, 206, 86, 0.9)',
                    'rgba(102, 204, 102, 0.9)',
                    'rgba(153, 102, 255, 0.9)',
                    'rgba(255, 159, 64, 0.9)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(102, 204, 102, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1,
            }]
    };
};


var ctxs = document.getElementsByClassName('chart');
for (var i = 3; i < 5; i++) {
    var ctx = ctxs[i];
    var myPieChart = new Chart(ctx,{
        type: 'doughnut',
        data: data_graph[i],
        datasets: [{
                // label: "My First dataset",
            }],
        options: {
            responsive: true,
            maintainAspectRatio: false
        },
    });
};

// доходность
var data_city_graph = {
    labels: names_stats[5],
    datasets: [
        {
            label: "Статистика по регионам",
            backgroundColor: [
                'rgba(0, 255, 0, 0.5)',
                'rgba(20, 255, 0, 0.5)',
                'rgba(40, 255, 0, 0.5)',
                'rgba(60, 255, 0, 0.5)',
                'rgba(80, 255, 0, 0.5)',
                'rgba(100, 255, 0, 0.5)',
                'rgba(120, 255, 0, 0.5)',
                'rgba(140, 255, 0, 0.5)',
                'rgba(160, 255, 0, 0.5)',
                'rgba(180, 255, 0, 0.5)',
                'rgba(200, 255, 0, 0.5)',
                'rgba(220, 255, 0, 0.5)'
            ],
            borderColor: [
                'rgba(0, 255, 0, 1)',
                'rgba(20, 255, 0, 1)',
                'rgba(40, 255, 0, 1)',
                'rgba(60, 255, 0, 1)',
                'rgba(80, 255, 0, 1)',
                'rgba(100, 255, 0, 1)',
                'rgba(120, 255, 0, 1)',
                'rgba(140, 255, 0, 1)',
                'rgba(160, 255, 0, 1)',
                'rgba(180, 255, 0, 1)',
                'rgba(200, 255, 0, 1)',
                'rgba(220, 255, 0, 1)'
            ],
            borderWidth: 1,
            data: data_stats[5],
        }
    ]
};
var ctx = document.getElementById('cityChart');
var cityChart = new Chart(ctx,{
    type: 'bar',
    data: data_city_graph,
    options: {
        legend: {
            display: false
        }
    }
});

</script>