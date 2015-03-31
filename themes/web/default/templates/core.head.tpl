<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
    <title><?=$title?></title>
    <meta name="keywords" content="<?=$keywords?>"/>
    <meta name="description" content="<?=$description?>"/>

    <? $now_id = 54; ?>

    <script type="text/javascript" src="<?=$themepath?>/js/jquery-latest.js?id=<?=$now_id?>"></script>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/allstyle.css?id=<?=$now_id?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/style.css?id=<?=$now_id?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/bcstyle.css?id=<?=$now_id?>"/>
    <link rel="stylesheet" href="<?=$themepath?>/styles/fontello.css?id=<?=$now_id?>" type="text/css">
    <link rel="stylesheet" href="<?=$themepath?>/styles/customicons.css?id=<?=$now_id?>" type="text/css">

    <script type = "text/javascript" src="<?=$themepath?>/js/jquery.jdpicker.js?id=<?=$now_id?>"></script>
    <link rel="stylesheet" href="<?=$themepath?>/styles/jdpicker.css?id=<?=$now_id?>" type="text/css" />

    <script type="text/javascript" src="<?=$themepath?>/js/device.min.js"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/some.js?id=<?=$now_id?>"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/table.js?id=<?=$now_id?>"></script>
    <script type='text/javascript' src='<?=$themepath?>/js/toolpit.js?id=<?=$now_id?>'></script>

    <link rel="shortcut icon" href="<?=$themepath?>/images/favicon.ico"/>
    <link rel="apple-touch-icon" href="<?=$themepath?>/images/apple-touch-icon.png"/>

</head>
<body>
<div class="all_content">
<div class="all_shadow">
    <div class="body">

        <div id="popup_load_overlay" class="popup_overlay">
            <div class="popup_table" id="place-form">
                <div style="text-align:center;">
                    <img style="height: 50px" src="<?=$themepath?>/images/load_small.gif"/>
                </div>
            </div>
        </div>

        <div id="floatTip">
            <div id="TipContainer"></div>
        </div>

<div class="loadmess" id="loadmess"> </div>

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
                               <td style="width:1%;"><a href="<?=core::$home?>"><img class="logo" src="<?=$themepath?>/images/logo.png"/></a></td>
                               <td style="width:100%;"> </td>
                           </tr>
                       </table>
                    </div>
                </td>
                <td class="userpanel_bg" rowspan="2">
                    <?if($user_id):?>
                    <table class="userarea">
                        <tr>
                            <td class="headavatar">
                                <?if($head_avatar):?>
                                <a href="<?=$home?>/user/profile"><img src="<?=$home?><?=$head_avatar?>"/></a>
                                <?else:?>
                                <a href="<?=$home?>/user/profile"><img src="<?=$themepath?>/images/defava.png"/></a>
                                <?endif?>
                            </td>
                            <td>
                                <a class="username" href="<?=$home?>/user/profile"><?=core::$user_name?></a><br/>
                                <a class="usercab" href="<?=$home?>/user/cab"><?=lang('private_cab')?></a><br/>

                                <form class="exitbut" method="POST" action="<?=$home?>/exit">
                                    <input type="hidden" name="act" value="do"/>
                                    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                                    <input class="exit_button" type="submit" value = "Выход"/>
                                </form>
                        </tr>
                    </table>
                    <?else:?>
                    <table class="userarea">
                        <tr>
                            <td class="headavatar">
                                <img src="<?=$themepath?>/images/defava.png"/>
                            </td>
                            <td>
                                <a class="username" href="<?=$home?>/login"><?=lang('login')?></a><br/>
                                <a class="usercab" href="<?=$home?>/user/recpassword"><?=lang('forgot_pass')?></a><br/>
                                <a class="usercab" href="<?=$home?>/user/register"><?=lang('register')?></a><br/>
                            </td>

                        </tr>
                    </table>
                    <?endif?>

                </td>
            </tr>
            <tr>
                <td class="und_head_cont">
                    <div class="headmenu">
                        <table class="topbutton">
                            <tr>
                                <td><a href="<?=$home?>">Главная</a></td>
                                <?if($rmenu):?>
                                <?foreach($rmenu as $rmenu):?>

                                   <td>
                                       <a href='<?=$rmenu['link']?>'><?=$rmenu['name']?>

                                            <?if($rmenu['one_cnt'] AND core::$user_id):?>
                                            <?if(counts::get($rmenu['one_cnt'])):?>
                                            <span class="cnt_round">+<?=counts::get($rmenu['one_cnt'])?></span>
                                            <?endif?>
                                            <?endif?>

                                       </a>
                                   </td>


                                <?endforeach?>
                                <?endif?>

                                <?if(core::$rights == 100):?>
                                    <td><a href="<?=$home?>/control"><?=lang('link_control_panel')?></a></td>
                                <?endif?>
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



         
