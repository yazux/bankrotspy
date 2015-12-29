<?php

defined('DS_ENGINE') or die('access denied');

$response = [];

$types = [
    'file'  => 'files',
    'image' => 'images'
];

$fileType = $_GET['type'];
$fileName = strtolower(basename($_FILES[$fileType]['name']));
$fileSource = $_FILES[$fileType]['tmp_name'];

if (!empty(intval($_GET['id']))) {
    $id = intval($_GET['id']);
    $uploadDir = 'data/mailing/' . $id . '/';
    $uploadfile = $uploadDir . $fileName;
    
} else {
    $id = 0;
    $uploadDir = 'data/tmp/mailing/';
    $uploadfile = $uploadDir . $fileName;
}

//загрузка
if($_GET['action'] == 'upload') {

    // если редактируем
    if(!empty($id)) {
        if(upload($fileSource, $uploadfile)) {
            core::$db->query('INSERT INTO `mail_mailing` (`mail_id`, `name`, `type`) 
                                VALUES("'.$id.'", "'.$fileName.'", "'.$fileType.'")');
            $response = [
                'error' => 0,
                'name'  => $fileName
            ];
        } else {
            $response = [
                'error' => 1
            ];
        }
    } else {

        if(upload($fileSource, $uploadfile)) {
            $response = [
                'error' => 0,
                'name'  => $fileName
            ];
        } else {
            $response = [
                'error' => 1
            ];
        }
    }
} 

//удаление
if($_GET['action'] == 'delete') {
    if(!empty($id)) {
        core::$db->query('DELETE `mail_mailing` WHERE name = "'.$fileName.'"');
        $uploadfile = 'data/mailing/' . $id . '/' . $_GET['file'];
        unlink($uploadfile);
    } else {
        $uploadfile = 'data/tmp/mailing/' . $_GET['file'];
        unlink($uploadfile);
    }
}

ajax_response($response);

function upload($source, $destination) {
    if (move_uploaded_file($source, $destination)) {
        return true;
    } else {
        return false;
    }
}