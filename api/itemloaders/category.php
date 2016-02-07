<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_category
{
    private $name;
    private $descr;

    function __construct($params)
    {
        $this->name = isset($params[0]) ?  trim($params[0]) : '';
        $this->descr = isset($params[1]) ?  trim($params[1]) : '';
    }

    private function merge_name($data_name, $description)
    {
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

    private function get_categories()
    {
        $outarr = array();
        $res = core::$db->query('SELECT * FROM `ds_main_cat_spec` ORDER BY sort ASC;');
        $i = 0;
  
        while ($data = $res->fetch_array()) {
        
            $outarr[$i]['id'] = $data['id'];
            
            $data['slova'] = mb_strtolower(str_replace('*', '', $data['slova']));
            $outarr[$i]['include'] = explode("\n", str_replace("\r\n", "\n", $data['slova']));
            
            $exclude = !empty($data['exclude']) ? explode("\n", str_replace("\r\n", "\n", $data['exclude'])) : '';
            $outarr[$i]['exclude'] = $exclude;
            
            $i++;
        }
        return $outarr;
    }

    public function process()
    {
        $categories = $this->get_categories();
        $lot = $this->merge_name($this->name, $this->descr);
        
        $all = [];
        
        $replace_arr = ['.', ',', ':', ';', '?', '/', '\\'];
        $lotdata = str_replace($replace_arr, ' ', $lot);
    
        $replace_arr = ['«', '»', '(', ')'];
        $lotdata = str_replace($replace_arr, '', $lotdata);
        //$estateCategory = $categories[5];unset($categories[5]);
    
        $estateExclude = [];
    
        //var_dump($categories);exit;
        foreach($categories as $category) {
               
            $id = $category['id'];
        
            if($id == 5) {
                foreach ($categories as $cat) {
                    if ($cat['id'] != $id) {
                        foreach ($cat['include'] as $word) {
                            $lot = preg_replace_callback('/\b('.mb_strtolower($word).')\b/ui', function($m) use(&$all, &$id) {
                                $all[$id]['exclude'][] = $m[0];
                                return ' ';
                            }, $lotdata);
                        }
                    }
                }
            } elseif(!empty($category['exclude'])) {        
                foreach($category['exclude'] as $word) { 
                    $lot = preg_replace_callback('/('.mb_strtolower($word).')/ui', function($m) use(&$all, &$id) {
                            $all[$id]['exclude'][] = $m[0];
                            return ' ';
                        }, $lotdata);
                }
            }
        
            //определяем точное совпадение по ключевым словам
            foreach($category['include'] as $word) {
                $estateExclude[] = $word;
                if (preg_match('#\b('.mb_strtolower($word).')\b#ui', $lot, $matches)) {
                    $all[$id]['include'][] = $matches[1];
                } elseif (preg_match('#('.mb_strtolower($word).')\b#ui', $lot, $matches)) {
                    $all[$id]['include'][] = $matches[1];
                }
            }
        }
    
        $cat = 0;
        if(!empty($all)) {
            $count = 0;
            
            $a = [];
            
            foreach($all as $i => $data) {
                $include = count($data['include']);
                $exclude = count($data['exclude']);
                if($i == 5) {
                    if($include > $count && $exclude <= 1) {
                        $count = $include;
                        $cat = $i;
                        $a = $data;
                    }
                } else {
                    if($include > $count) {
                        $count = $include;
                        $cat = $i;
                        $a = $data;
                    }
                }
            }
            $result[$cat] = $a;
        }
    
        // var_dump($all);
        /*foreach($result as $id => $r) {
            if(isset($r['include'])) {
                $data['id'] = $id;
                $data['include'] = $r['include'];
                $data['exclude'] = $r['exclude'];
            }
        }*/
        return $cat;
    }
}