<table>
    <tr>
        <td valign="top">

            <?if ($error):?>
            <div class="error">
                <?foreach($error as $error): ?>
                <?=$error?><br/>
                <?endforeach?>
            </div>
            <?endif?>

            <div class="content">
                <div class="conthead"><h2><i class="icon-newspaper"></i> <?=lang('edit_st')?></h2></div>

  
<?if($preview):?>


  <div class="conthead" style="border-bottom: 1px dotted #E4E4E4;background: white">
       <table>
          <tr>
            <td width="50px">
               <a title="<?=lang('us_create')?> <?=$arr['user']?>" href="<?=$home?>/user/profile?id=<?=$arr['userid']?>"><img class="avatar" src="<?=$arr['avatar']?>"/></a>
            </td>
            <td>
              <b><?=$arr['name']?></b><br/>
               <?if($arr['keys']):?>
               <?$a=0;?>  
                <?foreach ($arr['keys'] AS $ot_key=>$ot_value):?>
                  <?=$ot_value?><?if(count($arr['keys']) != ($a+1)):?>,<?endif?>
                  <?$a++;?>
                <?endforeach?>
               <?endif?>
             </td>
          </tr>
       </table>
  </div>
  <div class="contbodytext" style="border-bottom: 0px;"><div class="image_resizer"><?=$arr['text']?></div></div>
  <div class="contfintext" style="border-top: 1px dotted #E4E4E4;border-bottom: 1px solid #E4E4E4;background: white">
    <table>
      <tr>
        <td width="100%"> </td>
        <td title="<?=lang('stat_autor')?>"><a href="<?=$home?>/user/profile?id=<?=$arr['userid']?>"><i class="icon-user-1"></i><?=$arr['user']?></a></td>
      </tr>
    </table>
  </div>

    <div class="conthead">
        <h2><i class="icon-newspaper"></i> Редактирование:</h2>
    </div>

<?endif?>  
  

<form name="mess" action="<?=$home?>/articles/edit?id=<?=$id?>" method="post" enctype="multipart/form-data">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>

  <div class="contbody_forms">
    <b><?=lang('name_stat')?></b><br/>
    <input type="text" name="art_name" value="<?=$name?>" />
  </div>
  <div class="contbody_forms">
     <b><?=lang('data_opis')?></b><br/>
     <?=func::tagspanel('messarea');?>
     <div class="texta"><textarea id="messarea" name="art_text" rows="15"><?=$text?></textarea></div>
  </div>
  <div class="contbody_forms">
     <b><?=lang('data_keyw')?></b><br/>
     <div class="texta"><textarea name="art_keywords" rows="2"><?=$text_keys?></textarea></div>
  </div>
  <div class="contbody_forms">
    <b><?=lang('pr_file')?></b><br/>
    <input type="file" name="file" />
    <input type="submit" name="add_attachment" value="<?=lang('do_file')?>"/>
  </div>

  <?if($att_true):?>

   <div class="contbody_forms">
     <b><?=lang('pr_files_n')?></b><br/>
     <?foreach($out as $data):?>
       <hr/>
       <i class="icon-attach"></i>
       <input type="text" value="[<?=$data['type']?>=<?=$data['filename']?>]<?=$data['name']?>[/<?=$data['type']?>]"/>
       <input type="submit" name="del_attachment[<?=$data['id']?>]" value="<?=lang('del_th')?>"/>
       <b><a target="_blank" href="<?=$home?>/load/file<?=$data['id']?>/<?=$data['nameraw']?>"><?=$data['name']?></a></b>           
       
     <?endforeach?>
           
   </div>
  <?endif?>
       
   <div class="contfin_forms">
     <input name="submit" type="submit" value="<?=lang('save')?>" />
     <?if($preview):?>
       <input name="preview" class="button_noright" type="submit" value="<?=lang('preview')?>" /><input title="<?=lang('exitpreview')?>" class="button_noleft" name="exitpreview" type="submit" value="X" />
     <?else:?>
       <input name="preview" type="submit" value="<?=lang('preview')?>" />
     <?endif?>
     <!--<?if($hide_stat):?>
       <input name="public" type="submit" value="<?=lang('to_public')?>" />
     <?endif?>-->
   </div>

</form>

            </div>
        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Статьи:</div>
                <? temp::include('articles._lang.head.tpl') ?>
                <div class="elmenu"><a href="<?=$home?>/articles/post<?=$id?>"><?=lang('turn_back')?></a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>