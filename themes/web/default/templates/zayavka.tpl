<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
					<?
					$titleplusone='';
					if(isset($_GET['dann']))
					{
						$titleplusone=' / Ваши данные';
					}
					elseif(isset($_GET['step']) AND $_GET['step']==1)
					{
						$titleplusone=' / Опросный лист';
					}
					elseif(isset($_GET['step']) AND $_GET['step']==2)
					{
						$titleplusone=' / Просмотр документа';
					}
					elseif(isset($_GET['step']) AND $_GET['step']==3)
					{
						$titleplusone=' / Скачать';
					}
					?>
                    <h2><i class="icon-key"></i>Сервис заполнения заявки<?=$titleplusone?></h2>
					<?
					if($myid==1573)
					{
					//echo ''.$array_for_zaya['sqli'].'';
					//echo core::$user_id;
					}
					?>
                </div>
                <div class="contbody_forms">

<?
if(isset($_GET['files']) AND 2==1)
{
	?>
	<form method="post" enctype="multipart/form-data">
	<? temp::formid() ?>
	<input type="hidden" name="router" value="fileup">
	<input class="" type="file" name="file_photo" id="">
	<input type="submit" value="Загрузить">
	</form>
	<br>
	<br>
	<?
	if(!empty($array_for_zaya['myfilesthis']))
	{
	echo '
	    <table class="data_table" style="padding: 0px;">
		        <thead class="tableFloatingHeaderOriginal" style="position: static; margin-top: 0px; left: 27px; z-index: 3; width: 1304px; top: 0px;">
		    <tr>
			    <th colattr="" style="max-width: 200px; min-width: 0px;">Файл</th>
				<th colattr="" style="max-width: 200px; min-width: 0px;">Время</th>
			</tr>
		</thead>
		<tbody>
	';
	foreach($array_for_zaya['myfilesthis'] as $array_for_zaya_myfilesthis_k => $array_for_zaya_myfilesthis_v)
	{
	    ?>
		    <tr>
			    <td style="text-align:center;"><?=$array_for_zaya_myfilesthis_v?></td>
				<td style="text-align:center;">Файл</td>
			</tr>
		<?
	}
	echo '</tbody></table>';
	}
	else
	{
	    echo '<br><br>Вы не загружали файлы!';
	}
}
elseif(isset($_GET['docs']) AND function_orevel_search(array())==100)
{

	//print_r($ressw);
	//echo '<br>111';
	if(!empty($array_for_zaya['ressw']))
	{
		?>
		<form method="POST" action="<? echo $array_for_zaya['page'].'?docs'; ?>">
		<? temp::formid() ?>
		<input type="hidden" name="router" value="mydocs">
		<div style="display:inline-block; padding-right:10px; vertical-align:top;">
		<?
		foreach($array_for_zaya['ressw'] as $ress_k => $ress_v)
		{
		?>
			<span class="under">Документ №<?=$ress_v['id']?></span><br>
			<input type="text" name="doc<?=$ress_v['id']?>" value="<?=$ress_v['title']?>"><br>
		<?
		}
		?>
		</div>
		<br><input type="submit" value="Обновить данные по документам">
		</form>
		<?
	}
}
elseif(isset($_GET['dann']))
{
	//echo core::$user_id->rights;
	?>
	<form method="POST" action="<? echo $array_for_zaya['page'].'?dann'; ?>">
	<? temp::formid() ?>
	<input type="hidden" name="router" value="mydann">
	
	<div style="display:none; padding-right:10px; vertical-align:top;">
	
        <b>Полное наименование организатора торгов:</b><br>
        <span class="under">физ. лицо, ИП, ООО, ОА</span><br>
        <input type="text" name="my11" value="<?=$array_for_zaya['mydann']['my11']?>"><br>
		<br>
		
		<b>Место заполения заявки:</b><br>
        <span class="under">вписываем населенный пункт</span><br>
        <input type="text" name="my12" value="<?=$array_for_zaya['mydann']['my12']?>"><br>
		<br>
		
		<b>Дата заполнения заявки:</b><br>
        <span class="under">вписываем дату заполнения заявки</span><br>
        <input type="text" name="my13" value="<?=$array_for_zaya['mydann']['my13']?>"><br>
		<br>
		
		<b>Компания банкрот:</b><br>
        <span class="under">вписываем название компании банкрота</span><br>
        <input type="text" name="my14" value="<?=$array_for_zaya['mydann']['my14']?>"><br>
		<br>
		
		<b>Номер лота:</b><br>
        <span class="under">порядковый номер лота </span><br>
        <input type="text" name="my15" value="<?=$array_for_zaya['mydann']['my15']?>"><br>
		<br>
		
		<b>Наименование лота:</b><br>
        <span class="under">вписывает наименование лота</span><br>
        <input type="text" name="my16" value="<?=$array_for_zaya['mydann']['my16']?>"><br>
		<br>
		
		<b>Начальная цена лота:</b><br>
        <span class="under">вписываем начальную цену лота</span><br>
        <input type="text" name="my17" value="<?=$array_for_zaya['mydann']['my17']?>"><br>
		<br>
		
	</div>
	<div style="display:inline-block; padding-right:10px; vertical-align:top;">
	
        <span class="under">ФИО</span><br>
        <input type="text" name="my01" placeholder="Примеров Пример Примерович" value="<?=$array_for_zaya['mydann']['my01']?>"><br>
		<br>
		
		<span class="under">паспорт гражданина РФ</span><br>
        <input type="text" name="my02" placeholder="0000 № 000000, выдан 00.00.0000, в гор. Москва УФМС России по Московской обл. в Примерном р-не, код подразделения: 000-000" value="<?=$array_for_zaya['mydann']['my02']?>"><br>
		<br>
		
		<span class="under">зарегистрированный по адресу</span><br>
        <input type="text" name="my03" placeholder="000000, Московская обл., г. Москва, ул. Примерная, д. 000, кв. 000" value="<?=$array_for_zaya['mydann']['my03']?>"><br>
		<br>
		
		<span class="under">ИНН</span><br>
        <input type="text" name="my04" placeholder="" value="<?=$array_for_zaya['mydann']['my04']?>"><br>
		<br>
		
		<span class="under">тел.</span><br>
        <input type="text" name="my06" placeholder="" value="<?=$array_for_zaya['mydann']['my06']?>"><br>
		<br>
		
		<span class="under">адрес электронной почты</span><br>
        <input type="text" name="my07" placeholder="primer@primer.ru" value="<?=$array_for_zaya['mydann']['my07']?>"><br>
		<br>
	
	</div>
	<div style="display:inline-block; padding-right:10px; vertical-align:top;">
		<span class="under">ИП</span><br>
		<input type="text" name="ip01" placeholder="Примеров Пример Примерович" value="<?=$array_for_zaya['mydann']['ip01']?>"><br>
		<br>
		
		<span class="under">паспорт гражданина РФ</span><br>
        <input type="text" name="ip02" placeholder="0000 № 000000, выдан 00.00.0000, в гор. Москва УФМС России по Московской обл. в Примерном р-не, код подразделения: 000-000" value="<?=$array_for_zaya['mydann']['ip02']?>"><br>
		<br>
		
		<span class="under">зарегистрированный по адресу</span><br>
        <input type="text" name="ip03" placeholder="000000, Московская обл., г. Москва, ул. Примерная, д. 000, кв. 000" value="<?=$array_for_zaya['mydann']['ip03']?>"><br>
		<br>
		
		<span class="under">ИНН</span><br>
        <input type="text" name="ip04" placeholder="" value="<?=$array_for_zaya['mydann']['ip04']?>"><br>
		<br>
		
		<span class="under">ОГРНИП</span><br>
        <input type="text" name="ip05" placeholder="" value="<?=$array_for_zaya['mydann']['ip05']?>"><br>
		<br>
		
		<span class="under">тел.</span><br>
        <input type="text" name="ip06" placeholder="" value="<?=$array_for_zaya['mydann']['ip06']?>"><br>
		<br>
		
		<span class="under">адрес электронной почты</span><br>
        <input type="text" name="ip07" placeholder="primer@primer.ru" value="<?=$array_for_zaya['mydann']['ip07']?>"><br>
		<br>
	</div>
	<div style="display:inline-block; padding-right:10px; vertical-align:top;">
	
        <span class="under">ООО</span><br>
        <input type="text" name="myu1" placeholder="Общество с ограниченной ответственностью «Мегастрой»" value="<?=$array_for_zaya['mydann']['myu1']?>"><br>
		<br>
		
		<span class="under">В лице, должность</span><br>
        <input type="text" name="myu2" placeholder="генерального директора" value="<?=$array_for_zaya['mydann']['myu2']?>"><br>
		<br>
		
		<span class="under">действующий на основании</span><br>
        <input type="text" name="myu3" placeholder="устава" value="<?=$array_for_zaya['mydann']['myu3']?>"><br>
		<br>
		
		<span class="under">юридический адрес</span><br>
        <input type="text" name="myu4" placeholder="" value="<?=$array_for_zaya['mydann']['myu4']?>"><br>
		<br>
		
		<span class="under">почтовый адрес</span><br>
        <input type="text" name="myu5" placeholder="" value="<?=$array_for_zaya['mydann']['myu5']?>"><br>
		<br>
		
		<span class="under">тел</span><br>
        <input type="text" name="myu6" placeholder="" value="<?=$array_for_zaya['mydann']['myu6']?>"><br>
		<br>
		
		<span class="under">адрес электронной почты</span><br>
        <input type="text" name="myu7" placeholder="primer@primer.ru" value="<?=$array_for_zaya['mydann']['myu7']?>"><br>
		<br>
		
		<span class="under">ИНН</span><br>
        <input type="text" name="myu8" placeholder="" value="<?=$array_for_zaya['mydann']['myu8']?>"><br>
		<br>
		
		<span class="under">КПП</span><br>
        <input type="text" name="myu9" placeholder="" value="<?=$array_for_zaya['mydann']['myu9']?>"><br>
		<br>
		
		<span class="under">ОГРН</span><br>
        <input type="text" name="myu0" placeholder="" value="<?=$array_for_zaya['mydann']['myu0']?>"><br>
		<br>
	
	</div>
		
	<br><input type="submit" value="Обновить свои данные">
	</form>
	<?
}
elseif(isset($_GET['step']) AND $_GET['step']==1)
{
?>
<!--{fielddate}-->
<form method="POST" action="<? echo $array_for_zaya['page'].'?step=2'; ?>">
	<!--<input type="hidden" name="formid" value="8675309">-->
	<? temp::formid() ?>
	<input type="hidden" name="router" value="step2">
	
	<br><div class="zaya_db zaya_h1">Организационно правовая форма:</div>
	<label class="zaya_db"><input type="radio" name="field11" value="2" checked>Физическое лицо;</label>
	<label class="zaya_db"><input type="radio" name="field11" value="1">Индивидуальный предприниматель;</label>
	<label class="zaya_db"><input type="radio" name="field11" value="3">Юридическое лицо.</label>
	
	<br><div class="zaya_db zaya_h1">Документ:</div>
	<label class="zaya_db"><input type="radio" name="field21" value="1" checked>Аукцион;</label>
    <label class="zaya_db"><input type="radio" name="field21" value="2">Повторный аукцион;</label>
	<label class="zaya_db"><input type="radio" name="field21" value="3">Публичное предложение.</label>
	
    <div class="contbody_forms">
	</div>
	   
        <!--
		<br><span class="under">Кому, должность или Огр. форма</span><br>
        <input type="text" name="field31" value="" style="width:500px;" placeholder="Конкурсному управляющему ИЛИ Общество с ограниченной ответственностью">
		-->
		
		<script type="text/javascript" src="./themes/web/default/js/jquery.min.js"></script>
		<script type="text/javascript" src="./themes/web/default/js/jquery.jec.js"></script>
		
		<br><span class="under">Кому, должность или Огр. форма</span><br>
		<select id="s1" name="field31" style="width:520px;" placeholder="Ваш вариант">
		<option class="jecEditableOption" value=""></option>
		<option value="Конкурсному управляющему" selected>Конкурсному управляющему</option>
		<option value="Общество с ограниченной ответственностью">Общество с ограниченной ответственностью</option>
		
		</select>
		<script language="javascript">
		jQuery ("#s1").jec ({acceptedKeys: [{min:32, max:382}, {min:1024, max:1327}, {min:11744, max:11775}, {min:42560, max:42655}]});
		jQuery("#s1").jec({"blinkingCursor": true, "blinkingCursorInterval": 500});
		jQuery("#s1").jec();
		</script>
		
	    <br><span class="under">Кому, ФИО или название фирмы</span><br>
        <input type="text" name="field32" value="" style="width:500px;" placeholder="Примерову Примеру Примеровичу ИЛИ Название">
		<br><span class="under">Пункт Составления</span><br>
        <input type="text" name="field33" value="" placeholder="г. Москва">
		<br><span class="under">Номер лота</span><br>
        <input type="text" name="field34" value="" placeholder="Номер лота с нашего сайта">
		<br><span class="under">Ваша цена</span><br>
        <input type="text" name="field35" value="" placeholder="Ваша цена">
	
	<br><input type="submit" value="Перейти к документу">
</form>
<!---->
<?
}
elseif(isset($_GET['step']) AND $_GET['step']==2)
{
    //echo '<br>-'.$_POST['field21'].'-'.$_POST['field11'].'-';
	//$_POST['field11'] - IP/FL/UL
	//$_POST['field21'] - AU/PA/PP
	
	
	$iddocument=0;
	if(isset($_GET['id']))
	{
		$iddocument=$_GET['id'];
	}
	//
	
	//echo '<br>-'.$iddocument.'-';
	$doc = function_doc_search(array('doc'=>$_POST['field21'],'opf'=>$_POST['field11'],'iddocument'=>$iddocument,));
	//print_r($doc);
	
	
	//echo '<br>-'.$doc['id'].'-';
	if(isset($doc['id']) AND $doc['id']>0)
	{
	//замена в текстблоке полей
	$lots = function_doc_lots(array('lots'=>$_POST['field34'],));
	//print_r($_POST['field34']);
	//print_r($lots);
	$doc['textblock']=implode($lots['dept_name'],explode('{dept_name}',$doc['textblock']));
	$doc['textblock']=implode($lots['t1name'],explode('{t1name}',$doc['textblock']));
	//$doc['textblock']=number_format(implode($lots['t1price'],explode('{t1price}',$doc['textblock'])),2,',',' ');
	$doc['textblock']=implode($lots['t1price'],explode('{t1price}',$doc['textblock']));
	
	
	$sate = function_doc_date(array(''=>'',));
	$doc['textblock']=implode($sate,explode('{fielddate}',$doc['textblock']));
	
	$doc['textblock']=implode($_POST['field31'],explode('{field31}',$doc['textblock']));
	$doc['textblock']=implode($_POST['field32'],explode('{field32}',$doc['textblock']));
	$doc['textblock']=implode($_POST['field33'],explode('{field33}',$doc['textblock']));
	$doc['textblock']=implode($_POST['field35'],explode('{field35}',$doc['textblock']));
	
	//$array_for_zaya['mydann']['my01']
	$doc['textblock']=implode($array_for_zaya['mydann']['my01'],explode('{my01}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['my02'],explode('{my02}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['my03'],explode('{my03}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['my04'],explode('{my04}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['my05'],explode('{my05}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['my06'],explode('{my06}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['my07'],explode('{my07}',$doc['textblock']));
	
	$doc['textblock']=implode($array_for_zaya['mydann']['ip01'],explode('{ip01}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['ip02'],explode('{ip02}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['ip03'],explode('{ip03}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['ip04'],explode('{ip04}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['ip05'],explode('{ip05}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['ip06'],explode('{ip06}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['ip07'],explode('{ip07}',$doc['textblock']));
	
	$doc['textblock']=implode($array_for_zaya['mydann']['myu1'],explode('{myu1}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu2'],explode('{myu2}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu3'],explode('{myu3}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu4'],explode('{myu4}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu5'],explode('{myu5}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu6'],explode('{myu6}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu7'],explode('{myu7}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu8'],explode('{myu8}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu9'],explode('{myu9}',$doc['textblock']));
	$doc['textblock']=implode($array_for_zaya['mydann']['myu0'],explode('{myu0}',$doc['textblock']));
	
	//echo '<br>-'.$doc['id'].'-';
	$pag = function_doc_draw(array('doc'=>$doc,'num'=>$_POST['field21'],'opf'=>$_POST['field11'],'iddocument'=>$iddocument,));
	//echo ''.$pag.'';
	}
}
elseif(isset($_GET['step']) AND $_GET['step']==3)
{
    //echo 'Тут файлики';
	//echo '<br>-'.$array_for_zaya['sqli'].'-';
	if(!empty($array_for_zaya['mydoc']))
	{
	?>
	<table class="data_table" style="padding: 0px;">
        <thead class="tableFloatingHeaderOriginal" style="position: static; margin-top: 0px; left: 27px; z-index: 3; width: 1304px; top: 0px;">
		    <tr>
			    <th colattr="" style="max-width: 200px; min-width: 0px;">Название</th>
				<th colattr="" style="max-width: 200px; min-width: 0px;">Время</th>
				<th colattr="" style="max-width: 200px; min-width: 0px;">
					PDF
				</th>
			</tr>
		</thead>
		<tbody>
		    <?
			foreach($array_for_zaya['mydoc'] as $array_for_zaya_mydoc_k => $array_for_zaya_mydoc_v)
			{
			?>
		    <tr>
			    <td style="text-align:center;">
					<?
						if(!empty($array_for_zaya_mydoc_v['title_user']))
						{
							echo $array_for_zaya_mydoc_v['title_user'];
						}
						else
						{
							echo $array_for_zaya_mydoc_v['title'];
						}
					?>
				</td>
				<td style="text-align:center;"><? echo date("d-m-Y H:i",$array_for_zaya_mydoc_v['datatime']); ?></td>
				<td style="text-align:center;">
					<!--
					http://sergey.bankrot-spy.ru/pdfpage?doc=
					http://sergey.bankrot-spy.ru/zayavka?doc=
					-->
				    <a style="padding:0px 20px 0px 0px;" target="_blank" href="<? echo implode('pdfpage',explode('zayavka',$array_for_zaya['page_l1'])); ?>?doc=<?=$array_for_zaya_mydoc_v['id']?>">Скачать</a>
					
					
						<a tyle="display:inline-block; padding:3px 0px 3px 45px;" title="Редактировать" href="<?=$array_for_zaya['page']?>?step=2&id=<?=$array_for_zaya_mydoc_v['id']?>"><i class="icon-edit"></i></a>
					
					
						<a style="display:inline-block; padding:3px 0px 3px 5px;" title="Удалить" href="<?=$array_for_zaya['page']?>?step=3&did=<?=$array_for_zaya_mydoc_v['id']?>"><i class="icon-delete"></i></a>
					
				</td>
			</tr>
			<?
			}
			?>
		</tbody>
	</table>
	<?
	}
	else
	{
	    echo '<br><br>У Вас нет сформированных файлов!';
	}
}
else
{
    ?>
	В данном разделе Вы можете сформировать Заявку. Данные из личного кабинета переносятся в документ составленный Вами.
	<?
}
?>






                </div>
            </div>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <!--
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>">Описание</a></div>
				-->
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>?dann">Ваши данные</a></div>
				<!--
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>?files">Ваши файлы</a></div>
				-->
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>?step=1">Шаг 1. Опросный лист</a></div>
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>?step=2">Шаг 2. Просмотр документа</a></div>
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>?step=3">Шаг 3. Ваши заявки (скачать)</a></div>
				<?
				if(function_orevel_search(array())==100 AND 1==1)
				{
				?>
				<div class="elmenu"><a href="<?=$array_for_zaya['page']?>?docs">Названия документов</a></div>
				<?
				}
				?>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>



























<style>
.zaya_di    {display:inline-block;}
.zaya_db    {display:block;}
.zaya_h1    {font-size:20; font-weight:700;}
.zaya_h2    {font-size:16; font-weight:600;}
.zayz_gs2   {display:inline; margin:5px; padding:5px; border: 1px #c7c7c7 solid; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1); border-radius:9px;}
.zayz_gs2:hover   {box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2); border-radius:9px;}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".zayz_gs2").click(function(){
		//$("." + param_id).attr("style","");
	});
});
//
</script>