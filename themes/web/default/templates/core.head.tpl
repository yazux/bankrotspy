<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
    <title><?=$title?></title>
    <meta name="keywords" content="<?=$keywords?>"/>
    <meta name="description" content="<?=$description?>"/>

    <? $now_id = 78; ?>

    <script type="text/javascript" src="<?=$themepath?>/js/jquery-latest.js?id=<?=$now_id?>"></script>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/allstyle.css?id=<?=$now_id?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/style.css?id=<?=$now_id?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$themepath?>/styles/bcstyle.css?id=<?=$now_id?>"/>
    <link rel="stylesheet" href="<?=$themepath?>/styles/fontello.css?id=<?=$now_id?>" type="text/css">
    <link rel="stylesheet" href="<?=$themepath?>/styles/font-awesome.min.css?id=<?=$now_id?>" type="text/css">
    <link rel="stylesheet" href="<?=$themepath?>/styles/customicons.css?id=<?=$now_id?>" type="text/css">

    <script type = "text/javascript" src="<?=$themepath?>/js/jquery.jdpicker.js?id=<?=$now_id?>"></script>
    <link rel="stylesheet" href="<?=$themepath?>/styles/jdpicker.css?id=<?=$now_id?>" type="text/css" />

    <script type="text/javascript" src="<?=$themepath?>/js/device.min.js"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/number_format.js?id=<?=$now_id?>"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/table.js?id=<?=$now_id?>"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/some.js?id=<?=$now_id?>"></script>
    <script type="text/javascript" src="<?=$themepath?>/js/stickyTableHeaders.js"></script>
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
        
        <div id="note_window">
            <form>
            <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
            <input type="hidden" value="" name="id"/>
            <textarea name="text"></textarea>
            <i class="fa fa-floppy-o"> Сохранить</i><!--<i class="fa fa-trash"> Удалить</i>-->
            <i class="fa fa-times"></i>
            </form>
        </div>

        <div id="floatTip">
            <div id="TipContainer"></div>
        </div>

<div class="loadmess" id="loadmess"> </div>

<div class="alltopmess" id="alltopmess"> </div>
<?if($usc_mess_body):?>
   <script type="text/javascript">
      create_notify('<?=$usc_mess_body?>');
   </script>
<?endif?>

        <table class="allhead">
            <tr>
                <td>
                    <div class="prehead">
                       <table>
                           <tr>
                               <td style="width:1%;"><a href="<?=core::$home?>"><img class="logo" src="<?=$themepath?>/images/logo.png"/></a></td>
                               <td valign="top" style="width:20%;padding-top: 9px;">

                                  <span id="bookmark" class="in_bookmark"><i class="icon-bookmark"></i>В закладки</span>
                                   <script type="text/javascript">
                                       $(function() {
                                           $('#bookmark').click(function(e) {
                                               if (window.sidebar && window.sidebar.addPanel) { // Mozilla Firefox Bookmark
                                                   window.sidebar.addPanel(document.title,window.location.href,'');
                                               } else if(window.external && ('AddFavorite' in window.external)) { // IE Favorite
                                                   window.external.AddFavorite(location.href,document.title);
                                               } else if(window.opera && window.print) { // Opera Hotlist
                                                   this.title=document.title;
                                                   return true;
                                               } else { // webkit - safari/chrome
                                                   alert('Нажмите ' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') + ' + D чтобы добавить эту страницу.');
                                               }
                                               e.preventDefault();
                                           });
                                       });
                                   </script>
                               </td>
                               <td>
                                    <? $link = 'user/register'; ?>
                                    <? $open = ''; ?>
                                    <? if($user_id): ?>
                                        <? $link = 'data/materials/textbook.pdf'; ?>
                                        <? $open = 'target="_blank"'; ?>
                                    <? endif; ?>
                                    <a class="top-banner" href="<?= core::$home ?>/<?= $link ?>" <?= $open ?>>
                                        <img src="<?=$themepath?>/images/banners/textbook.gif"/>
                                    </a>
                               </td>
                           </tr>
                       </table>
                    </div>
                </td>
                <td class="userpanel_bg" >
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
                                <a class="usercab" href="<?=$home?>/user/cab"><?=lang('private_cab')?></a>&nbsp;&nbsp;

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
                                <a class="usercab" href="<?=$home?>/user/register"><?=lang('register')?></a>&nbsp;&nbsp;
                                <a class="usercab" href="<?=$home?>/user/recpassword"><?=lang('forgot_pass')?></a>
                                
                            </td>

                        </tr>
                    </table>
                    <?endif?>

                </td>
            </tr>
            <tr>
                <td class="und_head_cont" colspan="2">
                    <div class="headmenu">
                        <table class="topbutton">
                            <tr>
                                <td><a href="<?=$home?>">Главная</a></td>
                                <?if($rmenu):?>
                                <?foreach($rmenu as $rmenu):?>
                                    <? //if($rmenu['link'] == '/tariffs') continue; ?>
                                   <td>
                                       <a <?if($rmenu['new_tab']):?>target="_blank"<?endif?> <?= ($rmenu['link']== '/tariffs') ? 'style="font-weight:bold;color:#ff9b24;"' : ''?>href='<?=$rmenu['link']?>'>
                                       <?= ($rmenu['link']== '/tariffs') ? '<i class="icon-rouble"></i>' : '' ?>
                                       <?=$rmenu['name']?>

                                            <?if($rmenu['one_cnt'] AND core::$user_id):?>
                                            <?if(counts::get($rmenu['one_cnt'])):?>
                                            <span class="cnt_round">+<?=counts::get($rmenu['one_cnt'])?></span>
                                            <?endif?>
                                            <?endif?>

                                       </a>
                                       <? $link = core::$home.'/amc'?>
                                       <?if ($rmenu['link'] == $link):?>
                                        <ul class="subnav">
                                            <li><a href="/stats">Статистика лотов</a></li>
                                            <li><a href="/amc">Арб. управляющие</a></li>
                                            <li><a href="/platforms">Торговые площадки</a></li>
                                            <li><a href="/debtors">Должники</a></li>
                                        </ul>
                                        <?endif?>
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