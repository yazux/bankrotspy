
    <div class="conthead" style="border-bottom: 1px dotted #E4E4E4;background: white">
       <table>
          <tr>
            <td width="50px">
              <a title="<?=lang('us_create')?> <?=$out['user']?>" href="<?=$home?>/user/profile?id=<?=$out['userid']?>"><img class="avatar" src="<?=$out['avatar']?>"/></a>
            </td>
            <td>
              <b><a href="<?=$home?>/articles/post<?=$out['id']?>"><?=$out['name']?></a></b> <?if($out['on_moder']):?><span class="onmoder"><?=lang('onmoder')?></span><?endif?><?if($out['is_new']):?><sup class="newitem">[new]</sup><?endif?><br/>
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
          </tr>
       </table>
    </div>
    <div class="contbodytext_cut" style="border-bottom: 0px;"><div class="image_resizer"><?=$out['text']?></div></div>
    <div class="grback" style="border-bottom: 0px;"></div>
    <div class="contfintext" style="border-top: 1px dotted #E4E4E4;border-bottom: 1px solid #E4E4E4;background: white">
     <table>
      <tr>
        <td width="100%"><a class="urlbutton_stat" href="<?=$home?>/articles/post<?=$out['id']?>"><?=lang('read_now')?></a></td>
        <td title="<?=lang('stat_rating')?>"> Рейтинг: <b class="<?if($out['rating'] > 0):?>rating_plus_vum<?elseif($out['rating'] < 0):?>rating_minus_vum<?else:?>rating_num_vum<?endif?>"> <?if($out['rating'] > 0):?>+<?endif?><?=$out['rating']?></b></td>
        <td title="<?=lang('stat_comms')?>"><a href="<?=$home?>/articles/post<?=$out['id']?><?if($out['page_to_go']):?>/page<?=$out['page_to_go']?>#ncm<?else:?>#comms<?endif?>">Комментарии: <?=$out['comm_count']?> <?if($out['new_comm_count']):?><span class="newred">+ <?=$out['new_comm_count']?></span><?endif?></a></td>
        <td title="<?=lang('stat_autor')?>"><a href="<?=$home?>/articles/user?id=<?=$out['userid']?>">Автор: <?=$out['user']?></a></td>
      </tr>
    </table>
    </div>
    <div class="contfin_forms_delimiter">

    </div>
