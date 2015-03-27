<?if($com['seen']):?>
<div id="ncm" class="newcommentlower"><i class="icon-down"></i><?=lang('new_comment_down')?></div>
<?endif?>

<div class="contbody_und" id="comm<?=$com['id']?>">

<table>
  <tr>
    <td valign="top" width="50px">
       <a href="<?=$home?>/user/profile?id=<?=$com['id_user']?>"><img class="avatar" src="<?=$com['avatar']?>"/></a>
    </td>
    <td>
       <table style="margin-bottom:8px">
         <tr>
            <td width="100%">
             <a class="comm_username" href="<?=$home?>/user/profile?id=<?=$com['id_user']?>"><b><?=$com['from_login']?></b></a>
                <?if($com['rights']):?><span class="status">(<?=$com['rights']?>)</span><?endif?>
             <?if($com['online']):?><span class="us_on"> <?=lang('lang_on')?></span><?else:?><span class="us_off"> <?=lang('lang_off')?></span><?endif?><br/>
            </td>
            <td class="time">
               <?=$com['time']?>
            </td>
            <?if(comm::can_answer($com['id_user'], $com['rights_num'])):?>
            <td class="cont_act"><span title="<?=lang('answer_comm')?>" onclick="insertnick('<?=$com['from_login']?>')" class="pslink"><i class="icon-forward"></i></span></td>
            <td class="cont_act"><span title="<?=lang('quote_comm')?>" onclick="insertquote('<?=$com['from_login']?>', '<?=$com['to_quote']?>')" class="pslink"><i class="icon-quote-right"></i></span></td>
            <?endif?>
            <?if(!$comm_prev):?>
            <?if(comm::can_edit($com['id_user'], $com['rights_num'])):?>
              <td class="cont_act"><a title="<?=lang('edit_comm')?>" href="<?=$home?>/comms/edit?mod=<?=comm::$module?>&amp;act=<?=comm::$action?>&amp;id=<?=$com['id']?>&amp;mid=<?=comm::$mid?><?if(nav::$page):?>&amp;page=<?=nav::$page?><?endif?>"><i class="icon-edit"></i></a></td>
            <?endif?>
            <?if(comm::can_delete($com['id_user'], $com['rights_num'])):?>
              <td class="cont_act"><a title="<?=lang('del_comm')?>" href="<?=$home?>/<?=comm::$module?>/delcomm?mod=<?=comm::$module?>&amp;act=<?=comm::$action?>&amp;id=<?=$com['id']?>&amp;mid=<?=comm::$mid?><?if(nav::$page):?>&amp;page=<?=nav::$page?><?endif?>"><i class="icon-delete"></i></a></td>
            <?endif?>
           <?endif?>
         </tr>
       </table>
       <table><tr><td class="tmesscont"><div class="commtext"><div class="image_resizer"><?=$com['text']?></div></div></td></tr></table>
      <?if($com['useredit']):?><span class="messred"><?=lang('text_comm_edit')?> <a href="<?=$home?>/user/profile?id=<?=$com['editid']?>"><?=$com['useredit']?></a> <?=$com['time']?>. <?=lang('text_comm_all')?> <?=$com['numedit']?> <?=lang('text_comm_times')?>.</span><?endif?>
    </td>
  </tr>
</table>
</div>