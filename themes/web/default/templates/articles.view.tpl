<div class="content">
  <div class="conthead">
    <h2><?if($onmoder_stat):?><i class="icon-suitcase icon-theme"></i><?=lang('stats')?><?elseif($hidden_stat):?><i class="icon-suitcase icon-theme"></i> <?=lang('draft')?><?else:?><i class="icon-doc-text-inv icon-theme"></i><?=lang('stats')?><?endif?></h2>
  </div>

  <? temp::include('articles._lang.head.tpl') ?>
</div>

  <?if ($error):?>
    <div class="error">
      <?foreach($error as $error): ?>
        <?=$error?><br/>
      <?endforeach?>
    </div>
  <?endif?>

<div class="content">
  <div class="conthead">
       <table>
          <tr>
            <td width="50px">
               <a title="<?=lang('us_create')?> <?=$out['user']?>" href="<?=$home?>/user/profile?id=<?=$out['userid']?>"><img class="avatar" src="<?=$out['avatar']?>"/></a>
            </td>
            <td>
              <b><?=$out['name']?></b> <?if($onmoder_stat):?> <span class="onmoder">(<?=lang('onmoder')?>)</span><?endif?><br/>
              <?if($out['keys']):?>
                <i class="icon-tag"></i>
                <span class="tagtree">
               <?$a=0;?>
                <?foreach ($out['keys'] AS $ot_key=>$ot_value):?>
                  <a href="<?=$home?>/articles/tag<?=$ot_key?>"><?=$ot_value?></a><?if(count($out['keys']) != ($a+1)):?>,<?endif?>
                  <?$a++;?>
                <?endforeach?>
                </span>
               <?endif?>
               <?if($out['draft_keys']):?>
                  <i class="icon-tag"></i>
                  <span class="tagtree">
                    <?$a=0;?>
                       <?foreach ($out['draft_keys'] AS $ot_value):?>
                          <?=$ot_value?><?if(count($out['draft_keys']) != ($a+1)):?>,<?endif?>
                          <?$a++;?>
                       <?endforeach?>
                  </span>
                <?endif?>

             </td>
             <td class="timetd">
               <?=$out['time']?>
             </td>
              <?if(core::$user_id AND !$hidden_stat):?>
               <td class="cont_act">
                   <form id="choosen_form" action="<?=$home?>/articles/addtofav" method="post">
                       <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                       <input type="hidden" name="art_id" value="<?=$out['id']?>"/>
                       <span class="pslink" onClick="document.getElementById('choosen_form').submit()">
                         <?if($out['favtime']):?>
                           <i title="<?=lang('chit_title_ok')?>" class="icon-star"></i>
                         <?else:?>
                           <i title="<?=lang('chit_title')?>" class="icon-star-empty"></i>
                         <?endif?>
                       </span></td>
                   </form>
              <?endif?>
             <?if($can_ed_stat):?>
               <td class="cont_act"><a title="<?=lang('edit_stat')?>" href="<?=$home?>/articles/edit?id=<?=$out['id']?>"><i class="icon-edit"></i></a></td>
             <?endif?>
             <?if($can_del_stat):?>
               <td class="cont_act"><a title="<?=lang('delete_stat')?>" href="<?=$home?>/articles/delete?id=<?=$out['id']?>"><i class="icon-delete"></i></a></td>
             <?endif?>
          </tr>
       </table>
  </div>
  <div class="contbodytext"><div class="image_resizer"><?=$out['text']?></div></div>
  <div class="contfintext">
    <table>
      <tr>
        <td width="100%">
          <?if($onmoder_show_buttons):?>
            <form action="<?=$home?>/articles/acceptart?id=<?=$id?>" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input type="submit" class="art_moder_button" name="submit" value="<?=lang('art_agree')?>"/>
            </form>
            <form action="<?=$home?>/articles/declineart?id=<?=$id?>" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input type="submit" name="submit" value="<?=lang('art_decline')?>"/>
            </form>
          <?endif?>
          <?if($ondraft_stat):?>
            <form action="<?=$home?>/articles/public?id=<?=$id?>" method="post">
                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                <input type="submit" class="art_moder_button" name="submit" value="<?=lang('art_public')?>"/>
            </form>
          <?endif?>
          <?if(!$hidden_stat):?>
          <form action="" method="post">
          <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>

              <?if(core::$user_id):?>
                  <input <?if($vtype==2):?>class="inp_minus_prs"<?else:?>class="inp_minus"<?endif?> type="submit" name="add_minus" value="‒1"/>
              <?endif?>

              <span title="<?=lang('stat_rating')?>" class="<?if($out['rating'] > 0):?>rating_plus<?elseif($out['rating'] < 0):?>rating_minus<?else:?>rating_num<?endif?>">
                  <?if($out['rating'] > 0):?>+<?endif?><?=$out['rating']?>
              </span>

              <?if(core::$user_id):?>
                 <input <?if($vtype==1):?>class="inp_plus_prs"<?else:?>class="inp_plus"<?endif?> type="submit" name="add_plus" value="+1"/>
              <?endif?>
          </form>
          <?endif?>
        </td>
        <td title="<?=lang('stat_autor')?>"><a href="<?=$home?>/user/profile?id=<?=$out['userid']?>"><i class="icon-user-1"></i><?=$out['user']?></a></td>
      </tr>
    </table>
  </div>
</div>

<?if(!$hidden_stat):?>
<div id="comms" class="content">
  <div class="conthead">
     <table>
        <tr>
          <td>
            <b><?=lang('comments')?></b> (<?=$total?>)
          </td>
        </tr>
     </table>
  </div>

  <?foreach($com as $com): ?>

  <? temp::include('comms.class.show.tpl') ?>

  <?endforeach?>


  <?if($its_user):?>

    <?if($comm_prev_local):?>
    <div class="newcommentlower"><i class="icon-down"></i><?=lang('preview')?></div>

    <div class="contbody_und" id="comm<?=$com_pr['id']?>">

        <table>
            <tr>
                <td valign="top" width="50px">
                    <a href="<?=$home?>/user/profile?id=<?=$com_pr['id_user']?>"><img class="avatar" src="<?=$com_pr['avatar']?>"/></a>
                </td>
                <td>
                    <table style="margin-bottom:8px">
                        <tr>
                            <td width="100%">
                                <a class="comm_username" href="<?=$home?>/user/profile?id=<?=$com_pr['id_user']?>"><b><?=$com_pr['from_login']?></b></a>
                                <?if($com_pr['rights']):?><span class="status">(<?=$com_pr['rights']?>)</span><?endif?>
                                <?if($com_pr['online']):?><span class="us_on"> <?=lang('lang_on')?></span><?else:?><span class="us_off"> <?=lang('lang_off')?></span><?endif?><br/>
                            </td>
                            <td class="time">
                                <?=$com_pr['time']?>
                            </td>
                        </tr>
                    </table>
                    <table><tr><td class="tmesscont"><div class="commtext"><div class="image_resizer"><?=$com_pr['text']?></div></div></td></tr></table>
                </td>
            </tr>
        </table>
    </div>
    <?endif?>

  <form name="mess" id="crcomm" action="<?=$home?>/articles/post<?=$id?><?if($page):?>/page<?=$page?><?endif?>#crcomm" method="post">
  <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
  <div class="contbody_forms">
     <?=lang('add_com')?><br/>
     <?=func::tagspanel('messarea');?>
     <div class="texta"><textarea id="messarea" name="msg" rows="5"><?=$text_to_prev?></textarea></div>
  </div>

  <div class="contfintext">
     <input type="submit" name="submit" size="14" value="<?=lang('send')?>" />

     <?if($comm_prev_local):?>
       <input name="preview" class="button_noright" type="submit" value="<?=lang('preview')?>"/><input class="button_noleft" title="<?=lang('exitpreview')?>" name="exitpreview" type="submit" value="X"/>
     <?else:?>
       <input name="preview" type="submit" value="<?=lang('preview')?>"/>
     <?endif?>
  </div>
  </form>
  <?else:?>
     <div class="contfintext"><div class="total_info"><?=lang('reg_now')?></div></div>
  <?endif?>

</div>


<?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
<?endif?>
