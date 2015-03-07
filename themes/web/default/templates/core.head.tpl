<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
    <title><?=$title?></title>
    <meta name="keywords" content="<?=$keywords?>"/>
    <meta name="description" content="<?=$description?>"/>
    <script type="text/javascript" src="<?=$themepath?>/js/jquery-latest.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/allstyle.css"/>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/style.css"/>
    <link rel="stylesheet" href="<?=$themepath?>/styles/fontello.css" type="text/css">
    <link rel="stylesheet" href="<?=$themepath?>/styles/customicons.css" type="text/css">

    <script type="text/javascript" src="<?=$themepath?>/js/device.min.js"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/some.js"></script>

    <link rel="shortcut icon" href="<?=$themepath?>/images/favicon.ico"/>
    <link rel="apple-touch-icon" href="<?=$themepath?>/images/apple-touch-icon.png"/>

</head>
<body>
<div class="all_content">
<div class="all_shadow">
    <div class="body">

        <div id="popup_load_overlay" class="popup__overlay">
            <div class="popup_table" id="place-form">
                <div style="text-align:center;">
                    <img style="height: 50px" src="<?=$themepath?>/images/load_small.gif"/>
                </div>
            </div>
        </div>


<div class="alltopmess" id="alltopmess"> </div>
<?if($usc_mess_body):?>
   <script type="text/javascript">
      engine_mess('<?=$usc_mess_body?>');
   </script>
<?endif?>

        <table class="allhead">
            <tr>
                <td>
                    <div class="prehead">
                       <table>
                           <tr>
                               <td style="width:1%;"><img src="<?=$themepath?>/images/logo.png"/></td>
                               <td style="width:100%;"> </td>
                           </tr>
                       </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="headmenu">
                        <table class="topbutton">
                            <tr>
                                <td><a href="<?=$home?>/articles">Статьи</a></td>
                                <td><a href="<?=$home?>/forum">Форум</a></td>
                                <td><a href="<?=$home?>">Файлы</a></td>
                                <td><a href="<?=$home?>/albums">Альбомы</a></td>
                                <td><a href="<?=$home?>/albums">Полезные коды</a></td>
                                <td><a href="<?=$home?>/albums">Уголок писателя</a></td>
                                <?if(core::$rights == 100):?>
                                    <td><a href="<?=$home?>/control"><?=lang('link_control_panel')?></a></td>
                                <?endif?>
                                <td class="online"><a
                                            title="<?=lang('users_onl_text')?> (<?=$onl_users?>/<?=$onl_guests?>)"
                                            href="<?=$home?>/user/online"> <i class="icon-user-1"></i><?=$onl_all?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>


        <div class="mainall"></div>
        <table width="100%" height="100%">
            <tr>
                <td valign="top" id="mainwidth" class="main">



         
