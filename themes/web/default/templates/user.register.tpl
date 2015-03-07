<div class="content">
  <div class="conthead">
    <h2><?=lang('registration')?></h2>
  </div>

    <table class="contbutton">
      <tr>
        <td class="first"><a href="<?=$home?>/login"><?=lang('autorization')?></a></td>
        <td class="contbfin">&nbsp;</td>
      </tr>
    </table>
</div>

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
  <div class="conthead_forms">
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
    <b><?=lang('sex')?></b><br/>
        <select name="sex">
          <option value="">--</option>
          <option value="m" <?if($sex=='m'):?>selected="selected"<?endif?> ><?=lang('sex_m')?></option>
          <option value="w" <?if($sex=='w'):?>selected="selected"<?endif?> ><?=lang('sex_w')?></option>
        </select>
  </div>
  <div class="contbody_forms">
    <b><?=lang('capcha')?></b><br/>
    <?=$capcha?><br/>
    <input type="text" name="vcode" size="4" value="" />
  </div>
  <div class="contfin_forms">
    <input type="submit" value="<?=lang('save')?>" />
  </div>
</form>