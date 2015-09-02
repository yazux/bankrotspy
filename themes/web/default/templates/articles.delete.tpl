<div class="content">
  <div class="conthead">
       <table>
          <tr>
            <td>
              <b><?=lang('delete_st')?></b><br/>
             </td>
          </tr>
       </table>
  </div>
  <div class="contbodytext">
      <center><?=lang('del_text')?></center>
  </div>
  <div class="contfintext">
    <form name="mess" action="<?=$home?>/articles/delete?id=<?=$id?>" method="post">
    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
      <center><input name="submit" type="submit" value="<?=lang('delete_act')?>" />  <a class="urlbutton" href="<?=$home?>/articles/post<?=$id?>"><?=lang('cancel')?></a></center>
    </form>
  </div>
</div>