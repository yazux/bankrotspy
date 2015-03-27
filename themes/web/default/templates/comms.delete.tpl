<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
  <div class="conthead">
       <table>
          <tr>
            <td>
              <b><?=lang('del_comm_ask')?></b><br/>
             </td>
          </tr>
       </table>
  </div>
  <div class="contbodytext">
      <center><?=lang('del_text')?></center>
  </div>
  <div class="contfintext">
    <form name="mess" action="<?=$home?>/<?=$module?>/delcomm?mod=<?=$module?>&amp;act=<?=$action?>&amp;id=<?=$id?>&amp;mid=<?=$mid?><?if($page):?>&amp;page=<?=$page?><?endif?>" method="post">
    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
      <center><input name="submit" type="submit" value="<?=lang('delete_act')?>" />  <a class="urlbutton" href="<?=$home?>/<?=$module?>/<?=$action?>?id=<?=$mid?><?if($page):?>&amp;page=<?=$page?><?endif?>#comm<?=$id?>"><?=lang('cancel')?></a></center>
    </form>
  </div>
</div>