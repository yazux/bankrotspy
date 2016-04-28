<?php
//defined('DS_ENGINE') or die('web_demon laughs');

//CALC
$myid = core::$user_id;

//FUNC
function function_doc_search_id($param)
{
    $result = '';
	if(!empty($param['doc']) AND !empty($param['user']))
	{
	    //$result = $param['doc'];
		$sql1 = "SELECT `textblock` FROM `ds_textblock` WHERE `id`='".$param['doc']."' LIMIT 0,1";
		//AND `user_id`='".$param['user']."'
	    $res1 = core::$db->query($sql1);
	    if(!empty($res1))
	    {
	        foreach($res1 as $res1_k => $res1_v)
	    	{
	    	    $result = htmlspecialchars_decode($res1_v['textblock']);
	    	}
	    }
	}
	return $result;
}

$html='Вы не зарегистрированны';
if($myid>0 OR 1==1)
{
    $html = function_doc_search_id(array('doc'=>$_GET['doc'],'user'=>$myid,));
	//$html='<br>-'.$_GET['doc'].'-'.$myid.'-';
}

//include("./modules/mpdf57/mpdf.php");
include("./dscore/libs/mpdf57/mpdf.php");

$mpdf = new mPDF('utf-8', 'A4', '8', 'Arial', 3, 3, 9, 9, 0, 0);
    
	//$stylesheet = file_get_contents('http://sergey.bankrot-spy.ru/themes/web/default/styles/customicons.css');
    //$mpdf->WriteHTML($stylesheet,1);
	
	//$stylesheet = file_get_contents('http://sergey.bankrot-spy.ru/themes/web/default/styles/font-awesome.min.css');
    //$mpdf->WriteHTML($stylesheet,2);
	
	//$stylesheet = file_get_contents('http://sergey.bankrot-spy.ru/themes/web/default/styles/fontello.css');
    //$mpdf->WriteHTML($stylesheet,3);
	
	//$stylesheet = file_get_contents('http://sergey.bankrot-spy.ru/themes/web/default/styles/bcstyle.css');
    //$mpdf->WriteHTML($stylesheet,4);
	
	//$stylesheet = file_get_contents('http://sergey.bankrot-spy.ru/themes/web/default/styles/style.css');
    //$mpdf->WriteHTML($stylesheet,5);
	
    //$stylesheet = file_get_contents('./themes/web/default/styles/style.css');
    //$mpdf->WriteHTML($stylesheet,1);
	
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;


//temp::display('pdfpage');
//engine_fin();