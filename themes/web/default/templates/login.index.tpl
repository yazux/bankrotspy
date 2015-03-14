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
        <h2><?=lang('autorization')?></h2>
  </div>
  <div class="contbody_forms">
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

        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>/user/register"><?=lang('register')?></a></div>
                <div class="elmenu"><a href="<?=$home?>/user/recpassword"><?=lang('forg_pass')?></a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>