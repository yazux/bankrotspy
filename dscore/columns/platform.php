<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_platform
{
    private $pid;
    private $platforms = array();
    private $fedlink;
    public $access;

    function __construct($params)
    {
        $this->pid = isset($params[0]) ? $params[0] : '';
        $this->url = isset($params[1]) ? $params[1] : '';
        $this->fedlink = isset($params[2]) ? $params[2] : '';
        $this->access = (isset($params[3]) && $params[3] === true) ? true : false; 
    }

    public function before_load()
    {
        return array(
            'sortcolumn' => ' `ds_maindata`.`platform_id` ' //'sortcolumn' => ' `ds_maindata_platforms`.`platform_url` '
        );
    }

    public function name()
    {
        return array(
            'name' => 'Площадка, Федресурс',
            'nosort' => 1
        );
    }

    public function process()
    {
        $this->get_platforms();

        $links = array();
        
        if ($this->access === true) {
            if( $this->fedlink && (strlen($this->fedlink) > 6) ) {
                $links[] = '<a style="color:green;white-space:nowrap;" target="_blank" href="' . $this->fedlink . '"><i class="icon-globe-table"></i>fedresurs.ru</a>';
            }

            if( $this->url && (strlen($this->url) > 6) ) {
                $links[] = '<a style="color:green;white-space:nowrap;" target="_blank" href="' . $this->url . '"><i class="icon-globe-table"></i>' . $this->platforms[$this->pid] . '</a>';
            }

            $col = [
                'col' => implode('<br />', $links),
                'style' => 'text-align:center;',
                'addition' => ' onmouseover="toolTip(\'Если переход по ссылке ведет на страницу с ошибкой на площадке, значит торги завершены досрочно. Сделайте поиск на площадке по коду торгов.\')" onmouseout="toolTip()" '
            ];
        } else {
            $col = [
                'col' => '<i class="fa fa-lock"></i>',
                'style' => 'text-align:center;',
                'addition' => ' onmouseover="toolTip(\'Информация доступна на платной подписке\')" onmouseout="toolTip()" '
            ];
        }
        
        return $col;
    }

    private function get_platforms()
    {
        if(!rem::exists('maintable_platforms'))
        {
            $res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ;');
            while($data = $res->fetch_array())
            {
                $this->platforms[$data['id']] = $data['platform_url'];
            }
            rem::remember('maintable_platforms', serialize($this->platforms));
        }
        else
        $this->platforms = unserialize(rem::get('maintable_platforms'));
    }
}
