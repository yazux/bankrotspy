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
  
<form action="?act=save" method="post">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
<div class="content">
    <div class="conthead">
        <h2><?=lang('registration')?></h2>
    </div>
  <div class="contbody_forms">
    <b><?=lang('nick')?></b><br/>
    <span class="under"><?=lang('nick_und')?></span><br/>
    <input type="text" name="nick" value="<?=$nick?>" />
  </div>  
  <div class="contbody_forms">
    <b><?=lang('pass')?></b><br/>
    <span class="under"><?=lang('pass_und')?></span><br/>
    <input type="password" name="pass" value="<?=$pass?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('pass_rep')?></b><br/>
    <input type="password" name="pass_rep" value="<?=$pass_rep?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('mail')?></b><br/>
    <span class="under"><?=lang('mail_und')?></span><br/>
    <input type="text" name="mail" value="<?=$mail?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('capcha')?></b><br/>
    <?=$capcha?><br/>
    <input type="text" name="vcode" size="4" value="" />
  </div>
  <div class="contbody_forms">
     Внимание! Продолжая регистрацию вы соглашаетесь с <a href="http://bankrot-spy.ru/url?out=http%3A%2F%2Fbankrot-spy.ru%2Foferta.pdf">Публичным договором оферты</a>.
  </div>
  <div class="contfin_forms">
    <input type="submit" value="<?=lang('save')?>" />
  </div>
</form>


    </td>
    <td class="right_back_menu">
        <div class="right_panel_conf">
            <div class="menu_rt">Меню:</div>
            <div class="elmenu"><a href="<?=$home?>/login"><?=lang('autorization')?></a></div>
            <div class="down_rmenu"> </div>
        </div>
    </td>
  </tr>
</table>
