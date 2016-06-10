<?php

$id = (int)$_GET['id'];

if ( $id && isset($_FILES) && isset($_FILES['uploadfile']['name']) ) {
    $extention = end(explode(".", $_FILES['uploadfile']['name']));
    $newFileName = time();
    
    move_uploaded_file($_FILES['uploadfile']['tmp_name'], 'data/att_support/'.$newFileName.'.'.$extention);

    $fileId = core::$db->query('INSERT INTO `ds_post_files` SET
            `userload` = "'.core::$user_id.'",
            `module` = "support",
            `post` = '.$id.',
            `attach` = "0",
            `time` = "'.time().'",
            `name` = "'.$newFileName.'.'.$extention.'",
            `count` = "0"'
        );

    echo json_encode(array(
            'error'     => false,
            'fileName'  => $newFileName,
            'file'  => $newFileName.'.'.$extention,
        ));
    
} else {
    echo json_encode(array(
            'error'     => true,
        ));
}
die();