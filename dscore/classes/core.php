<?php
defined('DS_ENGINE') or die('web_demon laughs');

class core
{
    public static $lang = 'ru';            //Дефолтный язык движка
    public static $all_langs;
    public static $module = 'index';       //Загружаемый модуль
    public static $action = 'index';       //Действие модуля
    public static $folder;
    public static $ln;                     //языковой массив
    public static $db;                     //MySQLi
    public static $typetheme = 'pda';      //Тип темы
    public static $all_themes;             //Список всех тем по категориям
    public static $theme = 'default';      //тема движка
    public static $set;                    //настройки движка, таблица ds_settings
    public static $user_set;               //персональные настройки юзера
    public static $theme_path;             //полный путь к ресурсам темы
    public static $user_id;                //id юзера в системе
    public static $rights;                 //Массив с правами пользователя
    public static $user_name;              //Логин пользователя
    public static $user_sex;               //Пол пользователя
    public static $user_mail;
    public static $ua;                     //UserAgent
    public static $ip;
    public static $ipl;
    public static $home;
    public static $page_title;             //заголовок страницы
    public static $formid;
    public static $new_mail;               //Непрочитанные сообщения ЛС
    public static $last_post;              //ДАта последнего поста (func::antiflood())
    public static $user_md_pass;
    public static $smiles;
    public static $smiles_adm;
    public static $smile_code;
    public static $type_display;
    public static $avtime;
    public static $all_rights = array();
    public static $page_keywords;
    public static $page_description;
    public static $dest_time;
    
    private static $system_errors_langarr;

    public function __construct()
    {
        self::ipinit();
        self::system_set();
        self::data_connect();
        self::default_set();
        self::formid();
        self::set_lang();
        self::load_theme();
        self::initiate_module();
        self::user();
        self::system_clean();
        self::cron_imitate();
        //проверка подписки надо избавится от этого
        self::check_subsc();
    }

    private static function ipinit()
    {
        $ipinit = new ipinit();
        self::$ipl = $ipinit->ip;
        self::$ip = long2ip(self::$ipl);  
    }

    private static function system_set()
    {
        //Error_Reporting(E_ALL & ~E_NOTICE);
        @ini_set('session.use_trans_sid', '0');
        @ini_set('arg_separator.output', '&amp;');
        mb_internal_encoding('UTF-8');
        date_default_timezone_set('Europe/London');

        //стартуем сессию
        session_name('DSID');
        session_start();

        if (get_magic_quotes_gpc())
            self::del_mag_quotes();

        ob_start();
    }

    private static function del_mag_quotes()
    {
        //Взято из johnCMS
        // Удаляем слэши, если открыт magic_quotes_gpc
        $in = array(& $_GET, & $_POST, & $_COOKIE);
        while (list($k, $v) = each($in)) {
            foreach ($v as $key => $val) {
                if (!is_array($val)) {
                    $in[$k][$key] = stripslashes($val);
                    continue;
                }
                $in[] = & $in[$k][$key];
            }
        }
        unset ($in);
        if (!empty ($_FILES)) {
            foreach ($_FILES as $k => $v) {
                $_FILES[$k]['name'] = stripslashes((string) $v['name']);
            }
        }
    }
  
    private static function data_connect()
    {
        require_once('dscore/includes/db.php');
        self::$db = new data($db_host,$db_user,$db_pass,$db_name);
        self::$db->query('set names utf8');
        //self::$db->query('set sql_big_selects=1');  //Потом убрать
    }
  
    private static function default_set()
    {
        //дефаултные данные из базы
        $query = 'SELECT * FROM `ds_settings`;';
        $query .= 'SELECT * FROM `ds_smiles` WHERE `type` = "sm";';
        $query .= 'SELECT * FROM `ds_rights`;';

        core::$db->multi_query($query);

        // Разбираем таблицу ds_settings
        $req = core::$db->store_result();
        while ($res = $req->fetch_row())
            self::$set[$res[0]] = $res[1];
        self::$home = 'http://'.$_SERVER['HTTP_HOST'];
        if(self::$set['module'])
            self::$module = self::$set['module'];
        if(self::$set['action'])
            self::$action = self::$set['action'];
        self::$set['theme'] = unserialize(self::$set['theme']);
        self::$ua = htmlentities(substr($_SERVER['HTTP_USER_AGENT'], 0, 100), ENT_QUOTES);

        // Разбираем таблицу ds_smiles
        core::$db->next_result();
        $req = core::$db->store_result();
        self::$smile_code = rand(11111111, 999999999);
        $out_arr = array();
        $out_arr_adm = array();
        
        while ($result = $req->fetch_array()) {
            $sm_arr = explode(' ',$result['pattern']);
            foreach($sm_arr AS $value) {
                if($result['adm']==1)
                    $out_arr_adm[$value] = '[smile::'.$result['image'].'|'.self::$smile_code.']';
                else
                    $out_arr[$value] = '[smile::'.$result['image'].'|'.self::$smile_code.']';
            }
        }
        
        self::$smiles = $out_arr;
        self::$smiles_adm = $out_arr_adm;

        // Разбираем таблицу ds_rights
        core::$db->next_result();
        $res = core::$db->store_result();
        while($data = $res->fetch_array()) {
            $group_rights = unserialize($data['rights']);
            $rights = array();
            
            foreach ($group_rights as $group) {
                if (is_array($group)) {
                    foreach ($group as $key => $value) {
                        $rights[$key] = $value;
                    }
                }
            }
           
            $rights_arr[$data['id']] =  $rights;
        }
        self::$all_rights = $rights_arr;
    }
  
    private static function formid()
    {
        //Уникальный ид, для борьбы с межсайтовым скриптингом
        if(!isset($_SESSION['formid']))
            $_SESSION['formid'] = rand(11111111, 999999999);
        self::$formid = intval(abs($_SESSION['formid']));

        if($_POST) {
            if(!isset($_POST['formid']) || $_POST['formid'] != self::$formid) {
                header('Location: ' . self::$home);
                exit();
            }
        }
    }
    
    private static function set_lang()
    {
        //Все доступные для использования системой языки
        self::$all_langs = unserialize(self::$set['all_langs']);

        //Определяемся с языком
        if(self::$user_id and self::$user_set['lang'] and in_array(self::$user_set['lang'],self::$all_langs))
            self::$lang = self::$user_set['lang'];
        elseif(!self::$user_id and !empty($_COOKIE['ds_guest_lang']) and in_array($_COOKIE['ds_guest_lang'],self::$all_langs))
            self::$lang = $_COOKIE['ds_guest_lang'];
        else {
            $dect_lang = user::get_lang();
            if(in_array($dect_lang,self::$all_langs))
                self::$lang = $dect_lang;
            elseif(in_array(self::$set['lang'],self::$all_langs))
                self::$lang = self::$set['lang'];
        }
    }
  
    private static function load_theme()
    {
        self::$all_themes = array(
            'pda' => array('default'),
            'wap' => array('default'),
            'web' => array('default')
        ); 
      
        // Определяем тип темы 
        if(!self::$user_set['typetheme'] || !self::$all_themes[self::$user_set['typetheme']]) {
            self::$typetheme = self::detect_devise();  //переписать функцию и пару строчек ниже
            self::$type_display =  self::$typetheme;
        } else {
            self::$typetheme = self::$user_set['typetheme'];  
        }
   
        self::$typetheme = 'web';
         
        // Определяем тему 
        if(!self::$user_set['theme'][self::$typetheme] || !in_array(self::$user_set['theme'][self::$typetheme], self::$all_themes[self::$typetheme])) {
            if(self::$set['theme'][self::$typetheme] and in_array(self::$set['theme'][self::$typetheme],self::$all_themes[self::$typetheme]))  
                self::$theme = self::$set['theme'][self::$typetheme];
        } else {
            self::$theme = self::$user_set['theme'][self::$typetheme];
        }

        //полный путь к теме  
        self::$theme_path = self::$home.'/themes/'.self::$typetheme.'/'.self::$theme;   
    }
  
    private static function initiate_module()
    {
       
        self::$module = isset($_GET['core_mod']) ? $_GET['core_mod'] : self::$module;
        self::$folder = isset($_GET['folder']) ? $_GET['folder'] : false;
        self::$action = isset($_GET['core_act']) ? $_GET['core_act'] : self::$action;

        if(!empty(self::$folder)) {
            $file = 'modules/'.self::$module.'/'.self::$folder.'/'.self::$action.'.php';
            $language = 'languages/'.self::$lang.'/'.self::$module.'/'.self::$folder.'/'.self::$action.'.lang';
        } else {
            $file = 'modules/'.self::$module.'/'.self::$action.'.php';
            $language = 'languages/'.self::$lang.'/'.self::$module.'/'.self::$action.'.lang';
        }

        if(!preg_match('/[^0-9a-z\_]+/',self::$module) && !preg_match('/[^0-9a-z\_]+/',self::$action)) {
            if(!file_exists($file))
                self::error(3);
        } else {
            self::error(3);
        }

        self::$ln = self::parse_lang('languages/'.self::$lang.'/__add/all.lang');

        if(file_exists('languages/'.self::$lang.'/'.self::$module.'/--inc.lang'))
            self::$ln = array_merge(self::parse_lang('languages/'.self::$lang.'/'.self::$module.'/--inc.lang'), self::$ln);  //языковой файл

        if(file_exists($language)) {
            self::$ln = array_merge(self::parse_lang('languages/'.self::$lang.'/'.self::$module.'/'.self::$action.'.lang'), self::$ln);  //языковой файл
        } else {
            self::error(4);
        }
    }
  
    public function error($error='')
    {
        if($error) {
            if(!self::$system_errors_langarr) {
                if (file_exists('./languages/'.self::$lang.'/system_errors.lang'))
                    self::$system_errors_langarr = self::parse_lang('./languages/'.self::$lang.'/system_errors.lang');
            else
                die('Can\'t find file "/languages/'.self::$lang.'/system_errors.lang"');
            }
            
            if(self::$system_errors_langarr[$error])
                die(self::$system_errors_langarr[1].' '.self::$system_errors_langarr[$error]);
            else
                die('Can\'t fing error in the file "/languages/'.self::$lang.'/system_errors.lang"');
        } else {
            self::error(2);
        }
    }

    private static function user()
    {
        //Функция почти полность взята от johnCMS 4.x  
        $user_id = false;
        $user_pass = false;
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_pass'])) {
            $user_id = abs(intval($_SESSION['user_id']));
            $user_pass = $_SESSION['user_pass']; 
        } elseif (isset($_COOKIE['cuser_id']) && isset($_COOKIE['cuser_pass'])) {
            $user_id = abs(intval(base64_decode(trim($_COOKIE['cuser_id']))));
            $user_pass = md5(trim($_COOKIE['cuser_pass']));
            //----------
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_pass'] = $user_pass; 
        }
    
        if ($user_id and $user_pass) {
            $query = 'SELECT * FROM  `ds_users` WHERE `id` = "'.$user_id.'" and `password` = "'.self::$db->res($user_pass).'" LIMIT 1;';
        
            self::$db->multi_query($query);
      
            $res = core::$db->store_result();
            
            if($res->num_rows) {
                $data = $res->fetch_array();  
                self::$user_id = $user_id;
                self::$user_sex = $data['sex'];
                self::$user_name = $data['login'];
                if($data['settings'])
                    self::$user_set = unserialize($data['settings']);
                else
                    self::$user_set = array();
                self::$user_mail = $data['mail'];  

                //дописать права и др.  
                self::$rights = $data['rights'];
                self::$last_post = $data['lastpost'];
                self::$user_md_pass = $data['password'];
                self::$avtime = $data['avtime'];
                self::$dest_time = $data['desttime'];
                
                //записываем данные юзера в базу
                self::$db->query('UPDATE `ds_users` SET
                    `lastvisit`="'.time().'",
                    `ip`="'.self::$db->res(self::$ipl).'",
                    `ua`="'.self::$db->res(self::$ua).'",
                    `module`="'.self::$db->res(self::$module).'",
                    `action`="'.self::$db->res(self::$action).'"
                    WHERE `id` = "'.self::$user_id.'";'
                ); 
            } else {
                self::user_unset();
                self::$db->multi_free();
            }
        } else {
            //отмечаем гостя  
            $primid = md5(self::$ipl . self::$ua);
            $req = self::$db->query('SELECT * FROM `ds_guests` WHERE `primid` = "'.$primid.'" LIMIT 1');
            if(!$req->num_rows) {
                
                self::$db->query('INSERT INTO `ds_guests` SET
                    `primid`="'.$primid.'",
                    `lastdate`="'.time().'",
                    `ip`="'.self::$db->res(self::$ipl).'",
                    `ua`="'.self::$db->res(self::$ua).'",
                    `module`="'.self::$db->res(self::$module).'",
                    `action`="'.self::$db->res(self::$action).'" ;'
                );
            } else {
                self::$db->query('UPDATE `ds_guests` SET
                    `lastdate`="'.time().'",
                    `ip`="'.self::$db->res(self::$ipl).'",
                    `ua`="'.self::$db->res(self::$ua).'",
                    `module`="'.self::$db->res(self::$module).'",
                    `action`="'.self::$db->res(self::$action).'" 
                    WHERE `primid`="'.$primid.'" ;'
                );
            }
            unset($primid,$req);
        }  
    }
  
    public static function user_unset()
    {
        //НИКОГДА!!! НИКОГДА не вызывайте эту функцию в подкаталогах, иначе дикий батхерт обеспечен!!!  
        self::$user_id = false;
        unset($_SESSION['user_id']);
        unset($_SESSION['user_pass']);
        setcookie('cuser_id', '', 0 , '/');
        setcookie('cuser_pass', '', 0 , '/');
    }
  
    private static function system_clean()
    {
        $last_clean = self::$set['last_clean'];  
        if($last_clean < time()-(6*3600)) {
            $query = 'DELETE FROM `ds_guests` WHERE `lastdate` <  "'. (time() - 600) .'";';
            $query .= 'UPDATE `ds_settings` SET `val`="'.time().'" WHERE `key`="last_clean";';
            $query .= 'DELETE FROM `ds_users_inactive` WHERE `time` <  "'. (time() - (24*3600)) .'";';

            self::$db->multi_query($query);
            self::$db->multi_free();
        }
    }

    private static function cron_imitate()
    {
        $last_launch = self::$set['last_launch'];
        if($last_launch < time()-(12*3600)) {
            core::$db->query('UPDATE `ds_settings` SET `val`="'.time().'" WHERE `key`="last_launch";');

            new cron_imitator;
        }
    }

    public static function parse_lang($file)
    {
        //Переписанная функция parse_ini_file
        //Добавить возмлжность комментирования.
        $data_arr = file($file);
        $output = array();
        if(is_array($data_arr)) {
            foreach($data_arr as $key => $value) {
                if($value and mb_strpos($value, '=')) {
                    $pos = mb_strpos($value, '=');
                    $key = trim(mb_substr($value, 0, $pos));
                
                    if($key) {
                        $string = trim(mb_substr($value, $pos + 1));
                        $output[$key] = $string;
                    }
                }
            }
        }
        return $output;
    }

    private static function detect_devise()
    {
        $user_agent  = $_SERVER['HTTP_USER_AGENT'];
        $accept      = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

        $pda = 'pda';
        $wap = 'wap';
        $web = 'web';

        if(mb_stripos('ipad',$user_agent)!==false)
            return $pda;
        elseif(mb_stripos('ipod',$user_agent)!==false||mb_stripos('iphone',$user_agent)!==false)
            return $pda;
        elseif(preg_match('/android/i',$user_agent))
            return $pda;
        elseif(preg_match('/opera mini/i',$user_agent))
            return $pda;
        elseif(preg_match('/blackberry/i',$user_agent))
            return $pda;
        elseif(preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent))
            return $pda;
        elseif(preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent))
            return $pda;
        elseif(preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent))
            return $wap;
        elseif((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0))
            return $wap;
        elseif(isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE']))
            return $wap;
        elseif(in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',)))
            return $wap;
        else
            return $web;
    }

    /*
        проверка срока подписки
    */
    private static function check_subsc()
    {
        if(core::$user_id && (core::$rights == 10 || core::$rights == 11)) {
            if(core::$dest_time < time()) {
                core::$db->query('UPDATE `ds_users` SET
                    `rights` = "0",
                    `desttime` = "0"
                    WHERE `id` = "'.core::$user_id.'";'
                );

                header('Location:'.core::$home.'/tariffs/subend');
                exit();
            }
        }
    }
}
