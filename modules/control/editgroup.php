<?php

defined('DS_ENGINE') or die('web_demon laughs');

$error = array();

$id = abs(intval(GET('id')));

/*
if(!$id)
  denied();*/
/*
if($id > 99)
  denied();*/
  
$query = core::$db->query('SELECT * FROM `ds_rights` WHERE `id` = "'.$id.'";');

if ($query->num_rows) {
    $result = $query->fetch_assoc();  
    $group_rights = unserialize($result['rights']);
} else {
    $group_rights = $defaultRights;
}
    //var_dump($group_rights);
    $current_rights = array();
    
    foreach ($group_rights['common'] as $key => $value) {
        $current_rights['common'][] = $key;  
    }

    foreach ($group_rights['paid'] as $key => $value) {
        $current_rights['paid'][] = $key;  
    }    

    $names_r =  core::parse_lang('data/lang_rights/descr.lang');
    $nr =  core::parse_lang('data/lang_rights/rights.lang');

    $query = core::$db->query('SELECT * FROM `ds_rights` WHERE `id` = "100";');
    $result = $query->fetch_array();
    $rights = unserialize($result['rights']);
    
    
    $right_description = array(); //описание каждого поля
    $all_rights = array(); // все права
  
    //общие права на сайте
    foreach($rights['common'] as $key => $value) {
        $right_description['common'][$key] = $names_r[$key];
        $all_rights['common'][] = $key;
    }
    
    //платный контент
    foreach($rights['paid'] as $key => $value) {
        $right_description['paid'][$key] = $names_r[$key];
        $all_rights['paid'][] = $key;
    }
  
    $res = core::$db->query('SELECT * FROM `ds_rights` ORDER BY `id` DESC;');
    $rmenu = array();
    $all_ids = array();
    
    while ($dat = $res->fetch_array()) {
        if($dat['id'] != $id)  
        $all_ids[] = $dat['id'];
    }

    if (POST('submit')) {
        
        $name = POST('gr_fullname');  
        if(!$name)
            $error[] = lang('no_full_name');  
  
        $sname = POST('gr_shortname');
        if (!$sname) {
            $error[] = lang('no_sr_name');
        } elseif(mb_strlen($sname) > 5) {
            $error[] = lang('max_sr_name');
        }
        
        $sid = abs(intval(POST('gr_id')));
        /*
        if (!$sid) {
            $error[] = lang('no_id_name');
        } elseif($sid > 99 OR $sid < 1) {
            $error[] = lang('wr_id_name');
        } elseif(in_array($sid, $all_ids)) {
            $error[] = lang('wr_id_ren');
        }*/
        
        $rights = POST('rights');
        $new_rights = array();

        if (!$rights || !is_array($rights)) {
            $error[] = lang('no_rights');
            $rights = $new_rights;
        } else {
            $loc_err = false;  
            //общие права
            foreach ($rights['common'] as $value) {
                if (!in_array($value, $all_rights['common'])) {
                    $loc_err = true;
                } else {
                    $new_rights['common'][] = $value;
                }
            }
            //платный контент
            foreach ($rights['paid'] as $value) {
                if (!in_array($value, $all_rights['paid'])) {
                    $loc_err = true;
                } else {
                    $new_rights['paid'][] = $value;
                }
            }
            
       
            if ($loc_err) {
                $error[] = lang('wrong_rights');
            }
            $rights = $new_rights;        
        }
      
        if (!$error) {
            $rfile = core::parse_lang('data/lang_rights/rights.lang');
            unset($rfile['long_'.$id]);
            unset($rfile['short_'.$id]); 
            $rfile['long_'.$sid] = text::st($name); 
            $rfile['short_'.$sid] = text::st($sname); 
      
            $outfile = '';
            foreach ($rfile as $key=>$value) {
                $outfile .= $key.' = '.$value."\n";   
            }
            
            file_put_contents('data/lang_rights/rights.lang',$outfile,LOCK_EX);  
       
            $out_right = array();
            
            // общие права
            foreach ($rights['common'] as $key => $svalue) {
                $out_right['common'][$svalue] = 1;    
            }
            // платный контент
            foreach ($rights['paid'] as $key => $svalue) {
                $out_right['paid'][$svalue] = 1;    
            }
       
            core::$db->query('DELETE FROM `ds_rights` WHERE `id`="'.$id.'";');
            core::$db->query('UPDATE `ds_users` SET `rights` = "'.$sid.'" WHERE `rights`="'.$id.'";');
       
            core::$db->query('INSERT INTO `ds_rights` SET
                                `id` = "'.$sid.'" ,
                                `rights` = "'.core::$db->res(serialize($out_right)).'";');
       
            uscache::rem('mess_head', lang('mess_hesd'));
            uscache::rem('mess_body', lang('mess_body'));
        
            header('Location:'.core::$home.'/control/rights');
            exit(); 
        } 
    }
   
    engine_head(lang('admin_control'));
    if (isset($name)) {
        temp::assign('name', $name);
    } else {
        temp::assign('name', $nr['long_'.$id]);
    }
    
    if (isset($sname)) {
        temp::assign('sname', $sname);
    } else {
        temp::assign('sname', $nr['short_'.$id]);
    }
    
    if (isset($sid)) {
        temp::assign('sid', $sid);
    } else {
        temp::assign('sid', $id);
    }
  
    temp::assign('id', $id);
    temp::HTMassign('error', $error);
    
    temp::HTMassign('right_description', $right_description); // поля прав доступа

    if (POST('submit')) {
        temp::HTMassign('rights', $rights);
    } else {
        temp::HTMassign('rights', $current_rights);
    }

    temp::display('control.editgroup');
    engine_fin();