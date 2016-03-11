<?php

defined('DS_ENGINE') or die('web_demon laughs');

//перекинуть function это в файл INC, как будет доступ
function function_vals_new_correct($array){
	$return=0;
	$val1=$array['value'];
	$loc['now_price']=$array['now_price'];
	$loc['price']=$array['price'];
	$calcprice=0;
	if($loc['now_price']!=0)
	{
	    $calcprice=$loc['now_price'];
	}
	elseif($loc['price']!=0)
	{
	    $calcprice=$loc['price'];
	}
	$val1=implode('',explode(' ',$val1));
	$val1=implode('',explode('&amp;',$val1));
	$val1=implode('',explode('#160;',$val1));
	$auctionstep=rand(100,125)/100;
	$loc['tmp_price']=round($calcprice*$auctionstep,0);
	$val1=preg_replace('#<script[^>]*>.*?</script>#is', '', $val1);
	$val1=explode('руб',$val1);
	$val1=$val1[0];
	$val1=substr($val1,strlen($val1)-10,strlen($val1));
	$val1=implode('',explode(',00',$val1));
	$val1=implode('',explode('.00',$val1));
	$val1=preg_replace("/[^0-9]/", '', $val1);
	if($loc['now_price']!=0 AND $loc['now_price']<$val1)
	{
	    
	}
	elseif($loc['price']!=0 AND $loc['price']<$val1)
	{
	    
	}
	else
	{
	    $val1=$loc['tmp_price'];
	}
	$return=$val1;
	return $return;
}


//если нет доступа
if (!CAN('get_lot_price')) {
    $response = [
        'access' => 0
    ];
    ajax_response($response);
}

if(!isset($_SESSION['mp_count'])){
	$_SESSION['mp_count']=0;
	$_SESSION['mp_cache']=array();
}
else{
	$_SESSION['mp_count']++;
}

if(!empty($_POST) AND isset($_POST['id']) /*AND $_SESSION['mp_count']<5*/){

if(isset($_SESSION['mp_cache'][$_POST['id']])){
    
    $response = [
        'access'    => 1,
        'price'     => number_format($_SESSION['mp_cache'][$_POST['id']], 0, '.', ' ')
    ];
    
    ajax_response($response);
    
}
else{

$block_array=array(
    'bankrot-spy.ru',
    'bankrot.pro',
	'tbankrot.ru',
	'bankrot-pro.com',
	'ktobankrot.ru',
	'банкротинфо.рф',
	'probankrot.ru',
	'utender.ru',
	'bankrupt.centerr',
	'etp.kartoteka',
	'alfalot.ru',
	'bepspb.ru',
	'utpl.ru',
	'bankrupt.electro-torgi.ru',
	'arbitat.ru',
	'torgibankrot.ru',
	'meta-invest.ru',
	'bankrupt.etpu.ru',
	'bankrupt.etp-agenda.ru',
	'bankrupt.etp-agenda.ru',
	'tenderstandart.ru',
	'propertytrade.ru',
	'tendergarant.com',
	'uralbidin.ru',
	'lot-online',
	'fabrikant.ru',
	'm-ets.ru',
	'b2b-center.ru',
	'sberbank-ast.ru',
    'nistp.ru',
	'ausib.ru',
	'aukcioncenter.ru',
	'akosta.info',
	'sibtoptrade.ru',
	'atctrade.ru',
	'selt-online.ru',
	'regtorg.com',
	'etp-profit.ru',
	'seltim.ru',
	'cdtrf.ru',
	'el-torg.com',
	'eksystems.ru',
	'torgidv.ru',
	'auction63.ru',
	'promkonsalt.ru',
	'eurtp.ru',
	'торговая-площадка-вэтп.рф',
	'vtb-center.ru',
	'etp1.ru',
);




$res = core::$db->query("SELECT * FROM `ds_maindata` WHERE `id`='".core::$db->res($_POST['id'])."' AND `market_price`='0' AND `price`!='0.00'");
while($data = $res->fetch_array()){
	$loc=$data;
}
if(!empty($loc)){
$post_text_query=substr($loc['name'],0,75);
$post_text_query=trim($post_text_query).' цена';
//1
$post_text_query='https://yandex.ru/search/?text='.urlencode($post_text_query).'&lr=213';//50-prm//213-msc
$thispage=function_parce_page_tru(array('query'=>$post_text_query,));
$links=function_page_div_search(array('page'=>$thispage,'start'=>'<div class="link link_cropped_no serp-item__title-link"></div>',));
//2
sleep(3);
$post_text_queryg='https://yandex.ru/search/?text=google.com%20'.urlencode($post_text_query).'&lr=213';//50-prm//213-msc
$thispageg=function_parce_page_tru(array('query'=>$post_text_queryg,));
$linksg=function_page_div_search(array('page'=>$thispageg,'start'=>'<div class="link link_cropped_no serp-item__title-link"></div>',));
if(!empty($links)){
	if(!empty($linksg)){
		$links=array_merge($links,$linksg);
	}
	foreach($links as $key => $val){
		//echo '<textarea style="width:1200px; height:150px;">'.htmlspecialchars($val).'</textarea>';
		$val=explode('href="',$val);
		$val=$val[1];
		$val=explode('"',$val);
		$links[$key]=$val[0];
		
	}
}
$price_values=array();
if(!empty($links)){
	foreach($links as $key => $val){
		$val=explode('://',$val);$val=$val[1];
		$val=explode('/',$val);$val=$val[0];
		if(!in_array($val,$block_array)){
		$thispage=function_parce_page(array('query'=>$val,'type'=>'normal',));
		$thispage_vals=function_page_div_search_vals(array('page'=>$thispage,'val'=>'Цена',));
		//echo '+'.$key.'-PRICE-X:'.count($thispage_vals).'+<br>';
		if(count($thispage_vals)>0 AND count($thispage_vals)<=3){
			unset($thispage_vals);
			$thispage_vals=function_page_div_search_vals(array('page'=>$thispage,'val'=>'Руб',));
			if(!empty($links)){
				foreach($thispage_vals as $key1 => $val1){
					$val1=function_vals_new_correct(array('value'=>$val1,'now_price'=>$loc['now_price'],'price'=>$loc['price'],));
					$price_values[]=$val1;
				}
			}
		}
		}
		else{
		}
	}
}
$price_values_sum='';
//$price_values=array(555,666,600);
if(!empty($price_values)){
	rsort($price_values);
}

    //вывод для найденного
    $response = [
            'access'    => 1,
            'price'     => number_format($price_values[0], 0, '.', ' ')
        ];
    ajax_response($response);
    
	//запоминаем для найденного
    $_SESSION['mp_cache'][$_POST['id']]=$price_values[0];
	if($price_values[0]!=0)
	{
	//спросить у А.Петренко - можно ли делать UPDATE (локально все готово)
	core::$db->query("UPDATE `ds_maindata` SET `market_price`='".$price_values[0]."' WHERE `id`='".core::$db->res($_POST['id'])."'");
	}
}
else
{
    //все возникшие ошибки, или запрос цены у товара где ее не может быть - 0
	//вывод для не найденного
	$response = [
            'access'    => 1,
            'price'     => 0
        ];
    ajax_response($response);
	
	//запоминаем для не найденного
	$_SESSION['mp_cache'][$_POST['id']]=0;
}


}
}
?>