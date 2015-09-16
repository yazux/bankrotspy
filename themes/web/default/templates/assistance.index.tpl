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
  
<form action="?act=send" method="post">
<? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
<div class="content">
    <div class="conthead">
        <h2><?=lang('title')?></h2>
    </div>

    <div class="contbody_forms">
        Если Вам нужна помощь для участия в торгах и подачи заявок в нужное время, тогда отправьте нам заявку.
    </div>

  <div class="contbody_forms">
    <b><?=lang('name')?></b><br/>
    <span class="under"><?=lang('require')?></span><br/>
    <input type="text" name="name" value="<?= $name ?>" />
  </div>  
  <div class="contbody_forms">
    <b><?=lang('email')?></b><br/>
    <span class="under"><?=lang('require')?></span><br/>
    <input type="text" name="email" value="<?= $email ?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('skype')?></b><br/>
    <input type="text" name="skype" value="<?= $skype ?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('text')?></b><br/>
    <span class="under"><?=lang('require')?></span><br/>
    <div class="texta"><textarea name="text" rows="4"><?= $text ?></textarea></div>
  </div>
  <div class="contbody_forms">
    <b><?=lang('capcha')?></b><br/>
    <?=$capcha?><br/>
    <input type="text" name="vcode" size="4" value="" />
  </div>
   <input type="hidden" name="lotid" value="<?= $lotid ?>"/>
  <div class="contfin_forms">
    <input type="submit" value="<?=lang('send')?>" />
  </div>
</form>
</td>
</tr>
</table>
