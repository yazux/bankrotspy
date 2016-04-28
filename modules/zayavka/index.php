<?php
defined('DS_ENGINE') or die('web_demon laughs');

//CALC
$myid = core::$user_id;
$array_for_zaya['sqli'] = '';
$array_for_zaya['mydann'] = array();

//SERVER
$array_for_zaya['page'] = 'http://bankrot-spy.ru/zayavka';//http://sergey.bankrot-spy.ru/zayavka
$array_for_zaya['page_l1'] = 'http://bankrot-spy.ru/pdfpage';//http://sergey.bankrot-spy.ru/pdfpage

function function_file_in_url_join($param){
	$return='';
	$string_to_search='';
	$return=array();
	
	if(isset($param['url'])){
		$url=$param['url'];
		$dir = opendir($url);
		//$dir = opendir(iconv('utf-8', 'cp1251', $url));
		//$dir = opendir(iconv('Windows-1251', 'cp1251', $url));
		//$dir = opendir(iconv('ASCII', 'cp1251', $url));
		//$dir = opendir(iconv('cp1251', 'utf-8', $url));
		
		if(!empty($param['sea'])){
			$string_to_search=''.$param['sea'].'';
		}
		while(($file = readdir($dir)) !== false){
			if($file != '.' AND $file != '..'){
				if(!empty($string_to_search)){
					unset($file_all_x);
					$file_all_x = @strstr($file,$string_to_search);
					if($file_all_x!=''){
						if(isset($param['ex'])){
							$return[] = array(
								'name'=>iconv("Windows-1251","UTF-8",$file_all_x),
								'size'=>filesize($param['url'].$file_all_x),
								'time'=>filemtime($param['url'].$file_all_x),
							);
						}else{
						$return[] = $file_all_x;
						//$return[] = iconv("UTF-8","Windows-1251",$file_all_x);//utf8_encode//mb_check_encoding
						}
					}
				}else{
					if(isset($param['ex'])){
							$return[] = array(
								'name'=>iconv("Windows-1251","UTF-8",$file),
								'size'=>filesize($param['url'].$file),
								'time'=>filemtime($param['url'].$file),
							);
					}else{
					$return[] = $file;
					//$return[] = iconv("Windows-1251","UTF-8",$file);
					}
				}
			}
		}
		closedir($dir);
	}
	
	if(!empty($return) AND isset($param['size']) AND $param['size']=='y'){
		$array_for=array();
		$array_for['arr']=$return;
		$array_for['url']=$url;
		$return=function_filesize($array_for);
	}
	
	return $return;
}

//FUNC
function function_doc_search($param)
{
    $result = '';
	if(!empty($param['doc']))
	{
		
	    //$result = $param['doc'];
		
		//$param['doc'] - IP/FL/UL
		//$param['opf'] - AU/PA/PP
		
		$id=0;
		if($param['opf']==1)
		{
			$id=10;
		}
		elseif($param['opf']==2)
		{
			$id=13;
		}
		elseif($param['opf']==3)
		{
			$id=16;
		}
		
		if($param['doc']==1)
		{
			$id+=0;
		}
		elseif($param['doc']==2)
		{
			$id+=1;
		}
		elseif($param['doc']==3)
		{
			$id+=2;
		}
		
		$sql1 = "SELECT * FROM `ds_textblock` WHERE `id`='".$id."'";
	    $res1 = core::$db->query($sql1);
	    if(!empty($res1))
	    {
	        foreach($res1 as $res1_k => $res1_v)
	    	{
	    	    $result = $res1_v;
	    	}
	    }
	}
	return $result;
}

function function_doc_draw($param)
{
    global $array_for_zaya;
	global $_POST;
    if(!isset($param['doc']))
	{
	    $param['doc']='';
	}
	

	// - modules/ckeditor/
	// - dscore/libs/ckeditor/
	
	echo '
	<div style="margin:00px -10px 00px -10px;">
	<form method="POST" action="'.$array_for_zaya['page'].'?step=3">
	';
	temp::formid();
	echo '
	<input type="hidden" name="router" value="step3">
	<input type="hidden" name="doc" value="'.$param['doc']['id'].'">
	<script type="text/javascript" src="./dscore/libs/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="./dscore/libs/ckeditor/adapters/jquery.js"></script>
	<script type="text/javascript">$(\'#editor1\').ckeditor(function( textarea ){$(textarea).val();});</script>
	<textarea class="ckeditor" name="editor1" id="editor1" rows="3">'.($param['doc']['textblock']).'</textarea>
	<input type="submit" value="Утвердить документ">
	</form>
	</div>
	';
}

function function_doc_date($param)
{
    $d = date('«d»',time());
	$m = date('m',time());
	$ma = array(
	    '01'=>'января',
		'02'=>'февраля',
		'03'=>'марта',
		'04'=>'апреля',
		'05'=>'мая',
		'06'=>'июня',
		'07'=>'июля',
		'08'=>'августа',
		'09'=>'сентября',
		'10'=>'октября',
		'11'=>'ноября',
		'12'=>'декабря',
	);
	$m = $ma[$m];
	$y = date('Y г.',time());
	$return = ''.$d.' '.$m.' '.$y.'';
	return $return;
}

function function_doc_lots($param)
{
    $return = array();
	if(isset($param['lots']))
	{
		$param['lots'] = implode('',explode('Лот ',$param['lots']));
		$param['lots'] = implode('',explode('Лот',$param['lots']));
		$param['lots'] = implode('',explode('№1',$param['lots']));
		$param['lots'] = implode('',explode('№',$param['lots']));
		$param['lots'] = implode('',explode('Торги',$param['lots']));
		
	    $sqlg = "SELECT `t1`.`name` as `t1name`,`t1`.`price` as `t1price`,`t2`.* FROM `ds_maindata` as `t1` LEFT JOIN `ds_maindata_debtors` as `t2` ON `t1`.`debtor`=`t2`.`id` WHERE (`t1`.`id`='".$param['lots']."' OR `t1`.`name` LIKE '%".$param['lots']."%' OR `t1`.`code` LIKE '%".$param['lots']."%') ORDER BY `id` DESC LIMIT 0,1";
		$resg = core::$db->query($sqlg);
		if(1==1)
		{
			foreach($resg as $resg_k => $resg_v)
			{
				$return=$resg_v;
			}
		}
	}
	else
	{
	    //$return = 'NO SQL';
	}
	return $return;
}

//FILES
if(!empty($_FILES))
{
    //echo '<pre>'; print_r($_FILES); echo '</pre>';
	if(isset($_POST['router']) AND $_POST['router']=='fileup' AND isset($_FILES['file_photo']))
	{
	$extentions = array(
		'gif','jpg','png',
		'doc','docx','txt','xls','xlsx',
		//'mp3','mp4','avi','flv','mpeg',
	);
	$maxsize=1000000;
    
	$zagrujaem=0;
	if($_FILES['file_photo']['tmp_name']!='')
	{
		$file1 = $_FILES['file_photo']['name'];
		$file2 = $_FILES['file_photo']['tmp_name'];
		$size = $_FILES['file_photo']['size'];
		$file = $file2;
		$type = strtolower(substr($file1, 1 + strrpos($file1, ".")));
		$userdirectory = './data/userfile/'.$myid.'';
		if(!file_exists($userdirectory))
		{
		    if(mkdir($userdirectory))
			{
			}
		}
		$new_name = ''.$userdirectory.'/'.$file1.'.'.$type.'';
		$zagrujaem=1;
	}
	if($zagrujaem==1)
	{
	    $cont=0;
		if($size<$maxsize){$cont++;}else{$cont=0;/*echo 'Файл слишком большой';*/}
		if(in_array($type,$extentions)){$cont++;}else{$cont=0;/*echo 'Недопустимое расширение';*/}
		
			if($cont==2)
			{
				if(copy($file, $new_name))
				{
				}
			}
	}
	}
}
$array_for_zaya['sqli'] = '-'.$userdirectory.'-'.$zagr.'-';

//POST
if(
    isset($_GET['step']) AND $_GET['step']==3
	AND isset($_POST['router']) AND $_POST['router']=='step3'
	AND $myid>0
)
{
    $sqli="
		INSERT INTO `ds_textblock`
		(`user_id`,`razdel`,`title`,`textblock`,`datatime`)
		VALUES
		('".$myid."','1','".htmlspecialchars($_POST['doc'])."','".htmlspecialchars($_POST['editor1'])."','".time()."')
	";
    //core::$db->query($sqli);
	$array_for_zaya['sqli']=$sqli;
	core::$db->query($sqli);
}

//POST
if(isset($_POST['router']) AND $_POST['router']=='mydann')
{
    $sqls = "SELECT `t1`.`id` FROM `ds_users` as `t1` LEFT JOIN `ds_users_zaya` as `t2` ON `t1`.`id`=`t2`.`user_id` WHERE `t2`.`user_id`='".$myid."' LIMIT 0,1";
	$ress = core::$db->query($sqls);
	//print_r($ress);
	if(($ress->num_rows)==0)
	{
	    $sqli="INSERT INTO `ds_users_zaya` (`user_id`,`my01`,`my02`,`my03`,`my04`,`my05`,`my06`,`my07`,`my11`,`my12`,`my13`,`my14`,`my15`,`my16`,`my17`,`myu1`,`myu2`,`myu3`,`myu4`,`myu5`,`myu6`,`myu7`,`myu8`,`myu9`,`myu0`,`ip01`,`ip02`,`ip03`,`ip04`,`ip05`,`ip06`,`ip07`) VALUES ('".$myid."','".htmlspecialchars($_POST['my01'])."','".htmlspecialchars($_POST['my02'])."','".htmlspecialchars($_POST['my03'])."','".htmlspecialchars($_POST['my04'])."','".htmlspecialchars($_POST['my05'])."','".htmlspecialchars($_POST['my06'])."','".htmlspecialchars($_POST['my07'])."','".htmlspecialchars($_POST['my11'])."','".htmlspecialchars($_POST['my12'])."','".htmlspecialchars($_POST['my13'])."','".htmlspecialchars($_POST['my14'])."','".htmlspecialchars($_POST['my15'])."','".htmlspecialchars($_POST['my16'])."','".htmlspecialchars($_POST['my17'])."','".htmlspecialchars($_POST['myu1'])."','".htmlspecialchars($_POST['myu2'])."','".htmlspecialchars($_POST['myu3'])."','".htmlspecialchars($_POST['myu4'])."','".htmlspecialchars($_POST['myu5'])."','".htmlspecialchars($_POST['myu6'])."','".htmlspecialchars($_POST['myu7'])."','".htmlspecialchars($_POST['myu8'])."','".htmlspecialchars($_POST['myu9'])."','".htmlspecialchars($_POST['myu0'])."','".htmlspecialchars($_POST['ip01'])."','".htmlspecialchars($_POST['ip02'])."','".htmlspecialchars($_POST['ip03'])."','".htmlspecialchars($_POST['ip04'])."','".htmlspecialchars($_POST['ip05'])."','".htmlspecialchars($_POST['ip06'])."','".htmlspecialchars($_POST['ip07'])."')";
	}
	else
	{
	    $sqli="UPDATE `ds_users_zaya` SET `my01`='".htmlspecialchars($_POST['my01'])."',`my02`='".htmlspecialchars($_POST['my02'])."',`my03`='".htmlspecialchars($_POST['my03'])."',`my04`='".htmlspecialchars($_POST['my04'])."',`my05`='".htmlspecialchars($_POST['my05'])."',`my06`='".htmlspecialchars($_POST['my06'])."',`my07`='".htmlspecialchars($_POST['my07'])."',`my11`='".htmlspecialchars($_POST['my11'])."',`my12`='".htmlspecialchars($_POST['my12'])."',`my13`='".htmlspecialchars($_POST['my13'])."',`my14`='".htmlspecialchars($_POST['my14'])."',`my15`='".htmlspecialchars($_POST['my15'])."',`my16`='".htmlspecialchars($_POST['my16'])."',`my17`='".htmlspecialchars($_POST['my17'])."',`myu1`='".htmlspecialchars($_POST['myu1'])."',`myu2`='".htmlspecialchars($_POST['myu2'])."',`myu3`='".htmlspecialchars($_POST['myu3'])."',`myu4`='".htmlspecialchars($_POST['myu4'])."',`myu5`='".htmlspecialchars($_POST['myu5'])."',`myu6`='".htmlspecialchars($_POST['myu6'])."',`myu7`='".htmlspecialchars($_POST['myu7'])."',`myu8`='".htmlspecialchars($_POST['myu8'])."',`myu9`='".htmlspecialchars($_POST['myu9'])."',`myu0`='".htmlspecialchars($_POST['myu0'])."',`ip01`='".htmlspecialchars($_POST['ip01'])."',`ip02`='".htmlspecialchars($_POST['ip02'])."',`ip03`='".htmlspecialchars($_POST['ip03'])."',`ip04`='".htmlspecialchars($_POST['ip04'])."',`ip05`='".htmlspecialchars($_POST['ip05'])."',`ip06`='".htmlspecialchars($_POST['ip06'])."',`ip07`='".htmlspecialchars($_POST['ip07'])."' WHERE `user_id`='".$myid."'";
	}
	//$array_for_zaya['sqli']=$sqli;
	//echo $sqli;
	core::$db->query($sqli);
}

//ROU
if(isset($_GET['files']))
{
    $userdirs = 'data/userfile/'.$myid.'';
		if(!file_exists($userdirs))
		{
		    mkdir($userdirs);
		}
	$array_for_zaya['myfilesthis'] = function_file_in_url_join(array('url'=>$userdirs,));
	//$array_for_zaya['myfilesthis'] = array();
}

if(isset($_GET['dann']))
{
    $sqls = "SELECT `t2`.* FROM `ds_users` as `t1` LEFT JOIN `ds_users_zaya` as `t2` ON `t1`.`id`=`t2`.`user_id` WHERE `t2`.`user_id`='".$myid."' LIMIT 0,1";
	$ress = core::$db->query($sqls);
	if(($ress->num_rows)>0)
	{
	    foreach($ress as $ress_k => $ress_v)
		{
		    $array_for_zaya['mydann']=$ress_v;
		}
	}
}

if(isset($_GET['step']) AND $_GET['step']==2)
{
    $sql2 = "SELECT `t2`.* FROM `ds_users` as `t1` LEFT JOIN `ds_users_zaya` as `t2` ON `t1`.`id`=`t2`.`user_id` WHERE `t2`.`user_id`='".$myid."'";
	$res2 = core::$db->query($sql2);
	if(($res2->num_rows)>0)
	{
	    foreach($res2 as $res2_k => $res2_v)
		{
		    $array_for_zaya['mydann'] = $res2_v;
		}
	}
}

if(isset($_GET['step']) AND $_GET['step']==3)
{
    $array_for_zaya['mydoc']=array();
	$sql2 = "
		SELECT
			`t1`.`id`,
			`t2`.`title`,
			`t1`.`datatime`
		FROM `ds_textblock` as `t1`
		LEFT JOIN `ds_textblock` as `t2` ON `t1`.`title`=`t2`.`id`
		WHERE
			`t1`.`user_id`='".$myid."'
		ORDER BY
			`t1`.`id` ASC
		";
	$res2 = core::$db->query($sql2);
	if(!empty($res2))
	{
	    foreach($res2 as $res2_k => $res2_v)
		{
		    $array_for_zaya['mydoc'][] = $res2_v;
		}
	}
}

//Выводим страничку
engine_head('Заявка');
temp::HTMassign('array_for_zaya',$array_for_zaya);
temp::display('zayavka');
engine_fin();