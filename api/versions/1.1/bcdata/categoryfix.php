<?php
defined('DS_ENGINE') or die('web_demon laughs');

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
set_time_limit(-1);

// Получаем все категории
$categories = core::$db->query( 'SELECT * FROM ds_maindata_category' );
while ( $cat = $categories->fetch_assoc() ) {
    $allCategories[$cat['id']] = $cat['name'];
}
$allCategories[0] = 'Прочее';
//var_dump($allCategories);

// Получаем ключи категорий
$categoriesKeys = get_keys_category();

// Сколько выбираем лотов за раз
$limit = 5000;

// Получаем порцию лотов
$result = core::$db->query(
            '  SELECT *'
            .' FROM ds_maindata' 
            .' WHERE resorted = 0 AND status NOT IN (3, 4, 5, 6) AND end_time >= UNIX_TIMESTAMP()'
            .' LIMIT ' . $limit 
        );

$lots = array();
$lot = array();
$category = array();
while ( $row = $result->fetch_assoc() ) {

    $lot['id'] = $row['id'];
    $lot['currentCategory'] = $row['cat_id'];
    
    $lotText = merge_name( $row['name'], $row['description'] );
    $lot['text'] = $lotText;
    $category = getCategory($lotText, $categoriesKeys);
    
    $ik = 'Нет';
    if(isset($category['include']))
        $ik = implode(', ', $category['include']);

    $ek = 'Нет';
    if(isset($category['exclude']))
        $ek = implode(', ', $category['exclude']);
    
    $res = core::$db->query( "UPDATE ds_maindata SET cat_id = ".$category['id'].", resorted = 1 WHERE id = ".$lot['id']." LIMIT 1" );

    $lot['categoryId'] = $category['id'];
    $lot['include'] = $ik;
    $lot['exclude'] = $ek;
    
    //var_dump($lot);
    //var_dump('<br/><br/>');
    
    $lots[] = $lot;
}

//var_dump($lots);

// Сколько всего осталось
//$justLeft = core::$db->query(
//            '  SELECT COUNT(id)'
//            .' FROM ds_maindata' 
//            .' WHERE resorted = 0 AND status NOT IN (3, 4, 5, 6) AND end_time >= UNIX_TIMESTAMP()
//        ')->count();
//     
//engine_head(lang('admin_control'));
//temp::assign('justLeft', $justLeft);
//temp::assign('limit', $limit);
//temp::HTMassign('allCategories', $allCategories);
//temp::HTMassign('categoriesKeys', $categoriesKeys);
//temp::HTMassign('lots', $lots);
////temp::HTMassign('navigation', nav::display($total, core::$home.'/news?'));
//
//temp::display('control.categoryfix');
//engine_fin();

function merge_name($data_name, $description) {
    if (!$data_name) {
        $name = $description;
    } elseif($data_name != $description) {
        if (mb_substr_count($description, $data_name)) {
            $name = $description;
        } else {
            $name = trim($data_name) . '. ' . $description;
        }
    } else {
        $name = $data_name;
    }
    return mb_strtolower($name);
}

function get_keys_category() {

    $outarr = array();
    $res = core::$db->query( 'SELECT * FROM `ds_main_cat_spec` ORDER BY sort ASC;' );
    $i = 0;

    if ($res->num_rows) {
        while ( $data = $res->fetch_assoc() ) {
            $outarr[$i]['id'] = $data['id'];

//            $include = mb_strtolower(str_replace('*', '', $data['slova']));
            $outarr[$i]['include'] = explode("\n", str_replace("\r\n", "\n", $data['slova']));
//            var_dump($outarr[$i]['include']);
            $exclude = !empty($data['exclude']) ? explode("\n", str_replace("\r\n", "\n", $data['exclude'])) : '';
            $outarr[$i]['exclude'] = $exclude;

            $i++;
        }
    }
    return $outarr;
}

function getCategory($lotText, $keyList) {
    
    $allCategoryKeys = array();
    
    // Преобразуем все в маленькие символы
    $lotText = mb_strtolower( $lotText );
    
    // Вырезаем ненужные символы
    $replace_arr = ['.', ',', ':', ';', '?', '/', '\\'];
    $lotText = str_replace($replace_arr, ' ', $lotText);
    $replace_arr = ['«', '»', '(', ')'];
    $lotText = str_replace($replace_arr, '', $lotText);
    
    // Убираем лишние пробелы
    $lotText = preg_replace ('/\s+/', ' ',  $lotText);
    $lotText = trim($lotText);
    $lotText = ' ' . $lotText . ' ';
    
    // Проходим по всем категориям с ключами и стоп-словами
    foreach( $keyList as &$keys ) {
        
        // Запоминаем ID категории
        $id = $keys['id'];
        
        // Определяем какие стоп-слова присутствуют в тексте
        $excludeKeys = array();
        if(!empty($keys['exclude'])) {
            foreach($keys['exclude'] as $word) {
                $word = mb_strtolower($word);
                if ( !in_array( $word, $excludeKeys ) ) {
    //                echo $word.", ";
                    if ( substr_count($lotText, (' ' . $word . ' ') ) != false ) {
                        $excludeKeys[] = $word;
                    }
                }
            }
        }
        
        // Определяем какие ключи присутствуют в тексте
        $includeKeys = array();
        if(!empty($keys['include'])) {
            //var_dump($keys['slova']);
            foreach($keys['include'] as $word) {
                $word = mb_strtolower($word);
                if ( !in_array( $word, $includeKeys ) ) {
                    if ( substr_count($lotText, (' ' . $word . ' ') ) != false ) {
                        $includeKeys[] = $word;
                    }
                }
            }
        }

        $allCategoryKeys[$id]['id'] = $id;
        $allCategoryKeys[$id]['include'] = $includeKeys ? $includeKeys : null;
        $allCategoryKeys[$id]['exclude'] = $excludeKeys ? $excludeKeys : null;
        $allCategoryKeys[$id]['countInclude'] = $includeKeys ? count($includeKeys) : 0;
        $allCategoryKeys[$id]['countExclude'] = $excludeKeys ? count($excludeKeys) : 0;
        $allCategoryKeys[$id]['weight'] = $allCategoryKeys[$id]['countInclude'] - $allCategoryKeys[$id]['countExclude'];
    }
    
    // Если вообще не найдены ключи и антиключи
    $other = true;
    foreach($allCategoryKeys as $k => $allKeys ) {
        if( isset($allKeys['include']) || isset($allKeys['exclude']) ) {
            $other = false;
        }
    }
    
    if ( $other ) {
        $result = array();
        
        $result['id'] = 0;
        $result['countInclude'] = 0;
        $result['countExclude'] = 0;
        $result['weight'] = 0;
        
        return $result;
    }

    // Категория с наибольшим весом
    $idMax = 0;
    $maxWeight = -1000;
    foreach($allCategoryKeys as $k => $allKeys ) {
//        if( isset($allKeys['include']) && isset($allKeys['exclude']) ) {
            if ( $allCategoryKeys[$k]['weight'] > $maxWeight ) {
                $maxWeight = $allCategoryKeys[$k]['weight'];
                $idMax = $k;
            }
//        }
    }
    
    return $allCategoryKeys[$idMax];  
}