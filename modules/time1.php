<?php
$link = mysql_connect("176.9.149.46", "lifeuser", "brbayf6t");
	mysql_select_db("bankrotspyru"); 
	mysql_set_charset('utf8');
//-------------------------------------------
	$ttt=mysql_query("SELECT * FROM ds_maindata");
while ($ttt2=mysql_fetch_array($ttt))
		{
$rts2=$ttt2["start_time"];
$id=$ttt2["id"];
$n_str =strtotime($rts2); 
//str_replace(" ","",$rts2);
echo   $id.' '.$n_str;
 $x1=mysql_query("UPDATE ds_maindata SET start_time='".$n_str."' WHERE id=".$id);
echo '<br>';
			}





?>