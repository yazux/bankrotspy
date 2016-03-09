<?php

defined('DS_ENGINE') or die('web_demon laughs');

// доступ опционально для групп
if(!CAN('export_favorites')) {
    $response = array(
        'status'    => 1,
        'message'   => 'Данная функция доступна на тарифном плане VIP.'
    );
    ajax_response($response);
}

/*
//статусы ответа

0 - не корректные данные
1 - доступ запрещен
2 - прямая скачка файла
3 - файл отправлен на почту
*/

if(empty($_POST)) {
    $response = array(
        'status'    => 0,
        'message'   => 'Не корректные данные'
    );
    ajax_response($response);
}

// подключать через композер, переделать
require_once 'dscore/libs/PHPExcel.php';
require_once 'dscore/libs/phpmailer/PHPMailerAutoload.php';


$query = $query = core::$db->query('SELECT * FROM `ds_user_export` WHERE `user_id` = "'.core::$user_id.'" AND FROM_UNIXTIME(`datetime`) >= (INTERVAL -1 DAY + curdate())');
$countExport = $query->num_rows;

/*if($countExport >= 5) {
    $response = array(
        'status' => 3
    );
    
    echo json_encode($response);exit;
}*/


$fields = $_POST['fields']; //запрошенные столбцы
$fields['id'] = 'on';

$fieldsCount = count($fields); //всего столбцов


//массив полей 
$fieldsHeader = array(
    'name'          => 'Описание',
    'type'          => 'Тип',
    'region'        => 'Регион',
    'category'      => 'Категория',
    
    'begin_date'    => 'Дата начала',
    'end_date'      => 'Дата окончания',
    'status'        => 'Статус',
    
    'begin_price'   =>  'Начальная цена',
    'current_price' =>  'Текущая цена',
    'market_price'  =>  'Рыночная цена',
    
    'profit_rub'    =>  'Доход, руб.',
    'profit_percent'=>  'Доходность, %',
    'drop_price'    =>  'Понижение цены, %',
    
    'bankrupt'      =>  'Банкрот',
    'case_number'   =>  'Дело №',
    'bankrupt_inn'  =>  'ИНН банкрота',
    
    'organizer'     =>  'Организатор торгов',
    'organizer_inn' =>  'ИНН организатора',
    'arbitrator'    =>  'Арбитражный управляющий',
    'contact_person'=>  'Контактное лицо',

    'trades_link'    =>  'Торги на площадке',
    'fed_link'       =>  'Лот на федресурсе',
    'id'             =>  'Ссылка на БС'
);

// столбцы в которых текст по центру
$styleFields = array(
    'id',
    'category',
    'type',
    'region',
    'status',
    'begin_date',
    'end_date',
    'case_number',
    'begin_price',
    'bankrupt_inn',
    'current_price',
    'organizer_inn',
    'market_price',
    'drop_price',
    'profit_rub',
    'profit_percent',
    'trades_link',
    'fed_link'
);



$select = '';
$join = '';
$where = '';;


$sql = 'SELECT `ds_maindata`.*,
                `ds_maindata_category`.`name` AS `lot_category`,
                `ds_maindata_status`.`status_name`,
                `ds_maindata_regions`.`name` AS `region`,
                `ds_maindata_type`.`type_name`,
                `ds_maindata_debtors`.`dept_name`,
                `ds_maindata_debtors`.`inn` AS `bankrupt_inn`,
                `ds_maindata_organizers`.`org_name`,
                `ds_maindata_organizers`.`manager`,
                `ds_maindata_organizers`.`contact_person`,
                `ds_maindata_organizers`.`inn` AS `org_inn`
        FROM  `ds_maindata`
        LEFT JOIN `ds_maindata_favorive` ON 
            `ds_maindata`.`id` = `ds_maindata_favorive`.`item` AND 
            `ds_maindata_favorive`.`user_id` = "'.core::$user_id.'"
        LEFT JOIN `ds_maindata_category` ON 
            `ds_maindata`.`cat_id` = `ds_maindata_category`.`id`
        LEFT JOIN `ds_maindata_status` ON 
            `ds_maindata`.`status` = `ds_maindata_status`.`id`
        LEFT JOIN `ds_maindata_regions` ON 
            `ds_maindata`.`place` = `ds_maindata_regions`.`id`
        LEFT JOIN `ds_maindata_type` ON 
            `ds_maindata`.`type` = `ds_maindata_type`.`id`
        LEFT JOIN `ds_maindata_debtors` ON 
            `ds_maindata`.`debtor` = `ds_maindata_debtors`.`id`
        LEFT JOIN `ds_maindata_organizers` ON 
            `ds_maindata`.`organizer` = `ds_maindata_organizers`.`id`
        
        WHERE `ds_maindata_favorive`.`user_id` = "'.core::$user_id.'"
';

$query = core::$db->query($sql);
if($query->num_rows == 0) {
    $response = array(
        'status'    => 0,
        'message'   => 'У вас нет избранных лотов'
    );
    ajax_response($response);
}
//какие лоты есть в избранном для экспорта
$lotsExport = array();   
$i = 0;
while($data = $query->fetch_assoc()) {
    $lotsExport[] = $data['id'];
    
    if(!empty($fields['name'])) {
        $result[$i]['name'] = $data['name'];
    }
        
    if(!empty($fields['type'])) {
        $result[$i]['type'] = $data['type_name'];
    }

    if(!empty($fields['region'])) {
        $result[$i]['region'] = $data['region'];
    }
    
    if(!empty($fields['category'])) {
        $result[$i]['category'] = !empty($data['lot_category']) ? $data['lot_category'] : 'Прочее';
    }
           
    if(!empty($fields['begin_date'])) {
        $result[$i]['begin_date'] = date('d.m.Y', $data['start_time']);
    }
        
    if(!empty($fields['end_date'])) {
        $result[$i]['end_date'] = date('d.m.Y', $data['end_time']);
    }
        
    if(!empty($fields['status'])) {
        $result[$i]['status'] = $data['status_name'];
    }
    // стартовая цена
    if(!empty($fields['begin_price'])) {
        $result[$i]['begin_price'] = number_format($data['price'], 0 , ' ', ' ');
    }
    // текущая цена
    if(!empty($fields['current_price'])) {
        $result[$i]['current_price'] = number_format($data['now_price'], 0 , ' ', ' ');
    }
    // рыночкая цена
    if(!empty($fields['market_price'])) {
        $result[$i]['market_price'] = number_format($data['market_price'], 0 , ' ', ' ');
    }
        
    if(!empty($fields['profit_rub'])) {
        $result[$i]['profit_rub'] = number_format($data['profit_rub'], 0 , ' ', ' ');
    }
        
    if(!empty($fields['profit_percent'])) {
        $result[$i]['profit_percent'] = $data['profit_proc'];
    }
    // понижение цены
    if(!empty($fields['drop_price'])) {
        $result[$i]['drop_price'] = $data['price_dif'];
    }
    // банкрот
    if(!empty($fields['bankrupt'])) {
        $result[$i]['bankrupt'] = $data['dept_name'];
    }
    // номер дела
    if(!empty($fields['case_number'])) {
        $result[$i]['case_number'] = $data['case_number'];
    }
    
    // инн банкрота
    if(!empty($fields['bankrupt_inn'])) {
        $result[$i]['bankrupt_inn'] = $data['bankrupt_inn'];
    }

    // организатор торгов
    if(!empty($fields['organizer'])) {
        $result[$i]['organizer'] = $data['org_name'];
    }
    
    // инн организатора торгов
    if(!empty($fields['organizer_inn'])) {
        $result[$i]['organizer_inn'] = $data['org_inn'];
    }
    
    // арбитражный управляющий
    if(!empty($fields['arbitrator'])) {
        $result[$i]['arbitrator'] = array('id' => $data['organizer'], 'name' => $data['manager']);
    }
    
    // контактное лицо
    if(!empty($fields['contact_person'])) {
        $result[$i]['contact_person'] = $data['contact_person'];
    }
    
    if(!empty($fields['trades_link'])) {
        $result[$i]['trades_link'] = $data['auct_link'];
    }
        
    if(!empty($fields['fed_link'])) {
        $result[$i]['fed_link'] = $data['fedlink'];
    }
    
    $result[$i]['id'] = $data['id'];
    
    $i++;
}

$totalRow = count($result);

if(!empty($_POST['delete'])) {
    core::$db->query('DELETE FROM `ds_maindata_favorive` WHERE `user_id` = "'.core::$user_id.'"');
}

if(!empty($lotsExport)) {
    $lots = json_encode($lotsExport);
    core::$db->query('INSERT INTO `ds_user_export` (user_id, lots, datetime) VALUES("'.core::$user_id.'", "'.core::$db->res($lots).'", '.time().')');
}

$link_style_array = array(
    'font'  => array(
        'color' => array('rgb' => '0000FF'),
        'underline' => 'single'
    )
);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
//$excel->getActiveSheet()->setTitle('bankrot-spy.ru');
$sheet = $excel->getActiveSheet();

$sheet->freezePane('A3'); //закрепляем шапку
$sheet->getRowDimension(1)->setRowHeight(40); //высота шапки
$sheet->getRowDimension(2)->setRowHeight(20); //высота шапки

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath("data/bs.jpg");
$objDrawing->setWorksheet($sheet);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(10);
$objDrawing->setOffsetY(10);

$style_promo = array(
    // выравнивание
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
    ),
    'borders' => array (
        'allborders' => array (
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => 'ffffff'
            )
        ),
    )
);


$sheet->getStyle('A1:E1')->applyFromArray($style_promo);
$sheet->mergeCells('B1:c1');
$sheet->setCellValue('B1', 'www.bankrot-spy.ru');
$sheet->getCell('B1')->getHyperlink()->setUrl('http://www.bankrot-spy.ru');
$sheet->getStyle('B1')->applyFromArray($link_style_array);
//$sheet->setCellValue('A1', 'bankrot-spy.ru');


//$sheet->getStyle('A1')->applyFromArray($link_style_array);





$coord = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$coordIndex = 0;
// шапка

$row = 2;
$col = 0;

foreach($fields as $field => $data) {
    
    $cell = $coord[$coordIndex].$row;
    
    $sheet->setCellValue($cell, $fieldsHeader[$field]);
    $sheet->getColumnDimension($coord[$col])->setWidth(30);
    
    $col++;
    $coordIndex++;
}

//стиль шапки
$style_header = array(
    // выравнивание
    'alignment' => array(
        'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
    ),
    // заполнение цветом
    'fill' => array(
        'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
        'color'=>array(
            'rgb' => '43464b'
        )
    ),
    // шрифт
    'font'=>array(
        'bold' => true,
        /* 'italic' => true, */
        'name' => 'Arial',
        'size' => 11,
        'color' => array(
            'rgb' => 'ffffff'
        )
    ),
    'borders' => array (
        'allborders' => array (
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
                'rgb' => 'dddddd'
            )
        ),
    )
);
$sheet->getStyle('A2:' . $coord[$fieldsCount] .'2')->applyFromArray($style_header);

//список лотов
$coordIndex  = 0;

foreach($result as $row => $item) {
    $row = $row + 3;
    
    foreach($item as $field => $data) {
        $cell = $coord[$coordIndex].$row; //текущая ячейка
        
        //ссылка на площадку и фед. ресурс
        if ($field == 'id') {
            $sheet->setCellValue($cell, 'Карточка лота');
            $url = 'http://bankrot-spy.ru/cards/' . $data;
            $sheet->getCell($cell)->getHyperlink()->setUrl($url);
            
            $sheet->getStyle($cell)->applyFromArray($link_style_array);
        } elseif($field == 'arbitrator') {
            $sheet->setCellValue($cell, $data['name']);
            $url = 'http://bankrot-spy.ru/amc/' . $data['id'];
            $sheet->getCell($cell)->getHyperlink()->setUrl($url);
            $sheet->getStyle($cell)->applyFromArray($link_style_array);
        } else {
            $sheet->setCellValue($cell, $data);
        }
        if (in_array($field, $styleFields)) {
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        
        $coordIndex++;
    }
    $coordIndex  = 0;
    
}

$datetime = date('d.m.Y-H:i:s', time());

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$filename = 'data/export/bankrotspy_'.core::$user_id.'_' . $datetime . '.xlsx';
$objWriter->save($filename);


if(isset($_GET['action']) && $_GET['action'] == 'download') {
    $response = array(
        'status' => 2,
        'file' => $filename
    );

    ajax_response($response);    
} else {

    $body = array(
        'name' => core::$user_name,
        'date' => $datetime
    );

    //отправка на почту
    $mail = mailer::factory('./data/engine/');    
    $mail->setSubject('Избранные лоты');
    $mail->setBody('export', $body);
    $mail->addAttachment($filename);
    $mail->addAddress(core::$user_mail);

    if($mail->send()) {
        unlink($filename);
    }
    
    $response = array(
        'status' => 3,
        'message' => 'Файл отправлен на почту: ' . core::$user_mail
    );
    
    echo json_encode($response);
}