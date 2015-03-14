<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
  <div class="conthead">
       <table>
          <tr>
            <td>
              <b>Закрыть обращение #<?=$id?>?</b><br/>
             </td>
          </tr>
       </table>
  </div>
  <div class="contbodytext">
      <center>Вы действительно желаете закрыть обращение в техподдержку? <br/>После этого будет нельзя добавлять ответы в данное обращение.</center>
  </div>
  <div class="contfintext">
    <form name="mess" action="<?=$home?>/support/close?id=<?=$id?>" method="post">
    <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
      <center><input name="submit" type="submit" value="Закрыть" />  <a class="urlbutton" href="<?=$home?>/support/view?id=<?=$id?>">Отмена</a></center>
    </form>
  </div>
</div>