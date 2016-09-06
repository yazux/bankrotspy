<?php
defined('DS_ENGINE') or die('web_demon laughs');

$error = array();

if (!core::$user_id) {
 
    if(GET('act')) {  
        $login = POST('nick');
        $pass = POST('pass');
    
        if (!$login) {
            $error[] = lang('empty_nick');
        } elseif (!user::len_nick($login)) {
            $error[] = lang('nick_wrong_len');
        } elseif (!user::valid_nick($login)) {
            $error[] = lang('nick_wrong');
        }
    
        if (!$pass) {
            $error[] = lang('empty_pass');
        } elseif (mb_strlen($pass) < 4 or mb_strlen($pass) > 15) {
            $error[] = lang('pass_wrong_len');
        }
    
        if (!$error) {
            $req = core::$db->query('SELECT * FROM `ds_users` WHERE `login` = "'.core::$db->res($login).'" AND `password` = "'.core::$db->res(md5(md5($pass))).'"');  
            if ($req->num_rows) {
                //Стартуем сессию и все такое
                $user = $req->fetch_array();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_pass'] = md5(md5($pass));
         
                if (POST('mem')) {
                    $cuid = base64_encode($user['id']);
                    $cups = md5($pass);
                    setcookie('cuser_id', $cuid, time() + 3600 * 24 * 365);
                    setcookie('cuser_pass', $cups, time() + 3600 * 24 * 365);  
                }
        
                //Удаляем запись о юзере как о госте
                $primid = md5(core::$ipl . core::$ua);
                $req = core::$db->query('DELETE FROM `ds_guests` WHERE `primid` = "'.$primid.'";');
        
                header('Location: ' . core::$home . ''); exit();  
            } else {
                $error[] = lang('auth_err'); 
            }
        }
    }
}
    
engine_head(lang('autorization'));
if ($error) {
    temp::HTMassign('error',$error);
}

if (isset($login)) {
    temp::assign('nick',$login);  
}

temp::display('login.index');  
engine_fin();

