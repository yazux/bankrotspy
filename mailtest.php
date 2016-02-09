<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');


$str = '1;Кран козловой ККС 10 инвентарный номер № 492 Шаг аукциона5.00 %;693000;Суд: Арбитражный суд Ульяно
вской области;Аукцион;Приём заявок;Кран козловой ККС 10 инвентарный номер № 492 Шаг аукциона5.00 %;17
.01.2016 19:00;19.02.2016 19:00;693000;;;;;Чупринская Валентина Александровна  645311171793;http://bankrot
.fedresurs.ru/ArbitrManagerCard.aspx?ID=9422;Торги№887-ОАОФ-2лот№2;http://www.aukcioncenter.ru/trade
/view/purchase/general.html?id=101032357;А72-8329/2014;1;777;887-ОАОФ-1-1-aukcioncenter.ru;24;http:/
/bankrot.fedresurs.ru/TradeCard.aspx?ID=517731;http://tbankrot.ru/item?id=1742291;';

var_dump(explode(';', $str));

exit;

/*
$s = new SphinxClient;
$s->setServer("localhost", 6000);
$s->setMatchMode(SPH_MATCH_EXTENDED2);
$s->setMaxQueryTime(3);
//$s->SetLimits (0, 1500, 1500 );

$query = 'SELECT * FROM ds_maindata LIMIT 0, 20';
  $s->setSelect($query);
//$result = $s->query("квартира москва");

$s->AddQuery("квартира москва"); 
$result = $s->runQueries(); // поисковый запрос 


*/


//var_dump($result);

$conn= new mysqli('localhost', 'bankrotspy', 'E80KbRS8', 'bankrotspy', 6000);//'bankrotspy','E80KbRS8');
if ($conn->connect_error) {
throw new Exception('Connection Error: ['.$conn->connect_errno.'] '.$conn->connect_error, $conn->connect_errno);
}

$resource = $conn->query('SELECT * FROM ds_maindata WHERE MATCH(\'@description квартира москва\') LIMIT 10');
var_dump($resource);

//mysql_select_db('bankrotspy', $link);

//mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

//var_dump($result);

/*
foreach($result['matches'] as $key => $item) {
    var_dump($key);
    
    $query = mysql_query('SELECT name FROM ds_maindata WHERE id = "' . $key . '"');
    
    $row = mysql_fetch_assoc($query);
    
    //var_dump($row);
}
*/


/*
$regions = [
    //Центральный федеральный округ
    '1' => [
        'name' => 'Центральный федеральный округ',
        'items' => [31, 32, 33, 36, 37, 40, 44, 46, 48, 77, 50, 57, 62, 67, 68, 69, 71, 76],
    ],
    //Южный федеральный округ
    '2' =>  [
        'name' => 'Южный федеральный округ',
        'items' => [1, 30, 34, 8, 23, 61]
    ],
    
    //Северо-Западный федеральный округ
    '3' => [
        'name' => 'Северо-Западный федеральный округ',
        'items' => [29, 35, 39, 10, 11, 47, 51, 80, 53, 60, 78],
    ],
    //Дальневосточный федеральный округ
    '4' => [
        'name' => 'Дальневосточный федеральный округ',
        'items' => [79, 41, 49, 25, 14, 65, 27, 82, 28],
    ],
    //Сибирский федеральный округ
    '5' => [
        'name' => 'Сибирский федеральный округ',
        'items' => [4, 22, 3, 75, 38, 42, 24, 54, 55, 70, 17, 19],
    ],
    //Уральский федеральный округ
    '6' => [
        'name' => 'Уральский федеральный округ',
        'items' => [45, 66, 72, 81, 74, 83],
    ],
    //Приволжский федеральный округ
    '7' => [
        'name' => 'Приволжский федеральный округ',
        'items' =>[2, 43, 12, 13, 52, 56, 58, 59, 63, 64, 16, 18, 73, 21],
    ],
    //Северо-Кавказский федеральный округ
    '8' => [
        'name' => 'Северо-Кавказский федеральный округ',
        'items' => [5, 6, 7, 9, 15, 26, 20],
    ],
    //Крымский федеральный округ
    '9' => [
        'name' => 'Крымский федеральный округ',
        'items' => [84],
    ]
];

$link = mysql_connect('localhost', 'bsd', 'q1w2e3r4t5');
mysql_select_db('bsd', $link);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");


foreach($regions as $region) {
    $sql = 'INSERT INTO  ds_maindata_regions (name) VALUES("'.$region['name'].'")';
    $query = mysql_query($sql);
    
    $region_id = mysql_insert_id();
    
    foreach($region['items'] as $id) {
        $sql = 'UPDATE ds_maindata_regions SET parent_id = "'.$region_id.'" WHERE id = "'.$id.'"';
        mysql_query($sql);
    }
}

exit;
//phpinfo();exit;

error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once 'dscore/libs/phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.bankrot-spy.ru';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'no-reply@bankrot-spy.ru';                 // SMTP username
$mail->Password = 'C7q3H3u9';                           // SMTP password
$mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to
$mail->CharSet = 'UTF-8';
$mail->setFrom('no-reply@bankrot-spy.ru', 'Bankrot-Spy');

$mail->addAddress('imbagroup@yandex.ru');     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'bankrot-spy';
$mail->Body    = 'bankrot-spy test';
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
*/