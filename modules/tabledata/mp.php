<?
session_start();
header("Content-Type: text/html; charset=utf-8");
defined('DS_ENGINE') or die('web_demon laughs');

if(!isset($_SESSION['mp_count'])){
	$_SESSION['mp_count']=0;
	$_SESSION['mp_cache']=array();
}else{
	$_SESSION['mp_count']++;
}

if(!empty($_POST) AND isset($_POST['id']) AND $_SESSION['mp_count']<5){

if(isset($_SESSION['mp_cache'][$_POST['id']])){
	echo $_SESSION['mp_cache'][$_POST['id']];
}else{

function function_parce_page_tru($array){
	$baseurl='https://www.yandex.ru/';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$thispage = curl_exec($ch);
	curl_close($ch);
	sleep(3);
	
	$url=$array['query'];
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)");
	$thispage = curl_exec($ch);
	curl_close($ch);
	//echo '<textarea style="width:1200px; height:500px;">'.htmlspecialchars($thispage).'</textarea>';
	return $thispage;
}
function function_parce_page($array){
	$url=$array['query'];
	$ch = curl_init($url);
	//curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/45.0.2454.101 Chrome/45.0.2454.101 Safari/537.36');
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$thispage = curl_exec($ch);
	curl_close($ch);
	return $thispage;
}
function function_page_short($array){
	$result='';
	$page=explode($array['start'],$array['page']);
	$result=$page[$array['mode']];
	return $result;
}
function function_page_div_search($array){
	$result='';
	//echo '<textarea style="width:1200px; height:500px;">'.htmlspecialchars($array['page']).'</textarea>';
	$page=explode($array['start'],$array['page']);
	//echo count($page);
	return $page;
}
function function_page_div_search_vals($array){
	$result='';
	//echo '<textarea style="width:1200px; height:500px;">'.htmlspecialchars($array['page']).'</textarea>';
	$page=explode($array['val'],$array['page']);
	//echo count($page);
	return $page;
}

$res = core::$db->query("SELECT * FROM `ds_maindata` WHERE `id`='".$_POST['id']."' AND `market_price`='0'");
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
		$block_array=array(
			'bankrot-spy.ru',
			'bankrot.pro',
		);
		if(!in_array($val,$block_array)){
		$thispage=function_parce_page(array('query'=>$val,'type'=>'normal',));
		$thispage_vals=function_page_div_search_vals(array('page'=>$thispage,'val'=>'Цена',));
		//echo '+'.$key.'-PRICE-X:'.count($thispage_vals).'+<br>';
		if(count($thispage_vals)>0 AND count($thispage_vals)<=3){
			unset($thispage_vals);
			$thispage_vals=function_page_div_search_vals(array('page'=>$thispage,'val'=>'Руб',));
			if(!empty($links)){
				foreach($thispage_vals as $key1 => $val1){
					$val1=implode('',explode(' ',$val1));
					$val1=implode('',explode('&amp;',$val1));
					$val1=implode('',explode('#160;',$val1));
					$auctionstep=rand(25,110)/100;
					$loc['tmp_price']=round($loc['price']*$auctionstep,0);
					if(strlen($loc['tmp_price'])>3){
						$loc['tmp_price']=round($loc['tmp_price'],3);
					}
					//$val1 = preg_replace('/(<([^>]+)>)/U', '', $val1);//удалить тэги
					$val1 = preg_replace('#<script[^>]*>.*?</script>#is', '', $val1);
					$val1=explode('руб',$val1);
					//$val1=count($val1);
					$val1=$val1[0];
					$val1=substr($val1,strlen($val1)-10,strlen($val1));
					$val1=implode('',explode(',00',$val1));
					$val1=implode('',explode('.00',$val1));
					$val1 = preg_replace("/[^0-9]/", '', $val1);
					//echo '<textarea style="width:1200px; height:100px;">'.htmlspecialchars($val1).'</textarea>';
					if($val1>$loc['now_price'] AND $val1<($loc['price']*10)){
						$price_values[]=$val1;
					}else{
						$price_values[]=$loc['tmp_price'];
					}
				}
			}
		}
		}else{
		}
	}
}
$price_values_sum='';
//$price_values=array(555,666,600);
if(!empty($price_values)){
	rsort($price_values);
}
echo $price_values[0];//это значение отправляем обратно
$_SESSION['mp_cache'][$_POST['id']]=$price_values[0];
}else{
	echo 0;//все возникшие ошибки, или запрос цены у товара где ее не может быть - 0
	$_SESSION['mp_cache'][$_POST['id']]=0;
}
}
}
?>