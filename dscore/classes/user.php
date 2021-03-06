<?php
defined('DS_ENGINE') or die('web_demon laughs');

class user
{
    public static function get_lang()
    {
        $langcode = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $langcode = (!empty($langcode)) ? explode(';', $langcode) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode(',', $langcode['0']) : $langcode;
        $langcode = (!empty($langcode['0'])) ? explode('-', $langcode['0']) : $langcode;
        $langcode = isset($langcode[0]) ? $langcode : '';
        return $langcode;
    }

    public static function is_online($lastvisit)
    {
        return ($lastvisit > (time() - core::$set['onlinetime']));
    }

    public static function mail_exists($mail)
    {
        $req = core::$db->query('SELECT * FROM `ds_users` WHERE `mail` = "' . core::$db->res($mail) . '"');
        
        if ($req->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public static function exists($nick)
    {
        //Переписать на мульти_квери
        $req = core::$db->query('SELECT * FROM `ds_users` WHERE `login` = "' . core::$db->res($nick) . '"');
        $req2 = core::$db->query('SELECT * FROM `ds_users_inactive` WHERE `login` = "' . core::$db->res($nick) . '"');
        
        if ($req->num_rows or $req2->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public static function exists_id($id)
    {
        //Переписать на мульти_квери
        $req = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.core::$db->res($id).'"');
        if ($req->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_avatar($user_id, $avtime, $type = 0)
    {
        if($type)
            $postfix = '_small';
        else
            $postfix = '_small';

        if(file_exists('images/avatars/' . $avtime . '_' . $user_id . $postfix . '.png'))
            return core::$home . '/images/avatars/' . $avtime . '_' . $user_id . $postfix . '.png';
        else
            return core::$theme_path . '/images/user.png';
    }

    public static function valid_nick($nick)
    {
        // че за хуйня? переделать
        if(preg_match('/[^\da-zA-Z\-\@\!\_]+/u', $nick) && preg_match('/[^\dа-яА-ЯёЁ\-\@\!\_]+/u', $nick))
            return false;
        else
            return true;
    }

    public static function len_nick($nick)
    {
        if(mb_strlen($nick) < 2 or mb_strlen($nick) > 15)
            return false;
        else
            return true;
    }

    public static function get_rights($short = 0)
    {
        if(!rem::exists('ds_rights_names')) {
            $lang_rights = core::parse_lang('data/lang_rights/rights.lang');
            $rights_arr = array();
            
            foreach(core::$all_rights AS $key => $value)
            {
                if(!$short)
                    $rights_arr[$key] = $lang_rights['long_' . $key];
                else
                    $rights_arr[$key] = $lang_rights['short_' . $key];
            }
            rem::remember('ds_rights_names', $rights_arr);
        }
        return rem::get('ds_rights_names');
    }
}