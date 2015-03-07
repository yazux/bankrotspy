<div class="content">
  <div class="conthead">
    <h2><?=lang('autorization')?></h2>
  </div>

    <table class="contbutton">
      <tr>
        <td class="first"><a href="<?=$home?>/user/register"><?=lang('register')?></a></td>
        <td class="first"><a href="<?=$home?>/user/forgpass"><?=lang('forg_pass')?></a></td>
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
     <input type="text" name="nick" value="<?=$nick?>" />
  </div>
  <div class="contbody_forms">
     <b><?=lang('pass')?></b><br/>
     <input type="password" name="pass" value="" /><br/>
     
     <input type="checkbox" id="memch" name="mem" checked="checked" value="1"/>
     <label onclick="javascript: return (document.getElementById('memch') ? false : true)" for="memch" title="Отметить">
          <a href="#" onmousedown="document.getElementById('memch').checked = (document.getElementById('memch').checked ? false : true);">
            <?=lang('remember')?>
          </a>
     </label>
  </div>
  <?if($capcha):?>
     <div class="contbody_forms">
       <b><?=lang('capcha')?></b><br/>
       <?=$capcha?><br/>
       <input type="text" name="vcode" size="4" value="" />
     </div>
  <?endif?>
  <div class="contfin_forms">
     <input type="submit" value="<?=lang('save')?>" />
  </div>
</div>
</form>