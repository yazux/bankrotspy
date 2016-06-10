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
         <?= $page_text ?>
    </div>

    <div class="contbody_forms">
      <b><?=lang('comp')?></b><br/>
      <span class="under"><?=lang('require')?></span><br/>
      <select name="company">
        <? foreach( $companies as $company ) :?>
            <option value="<?= $company['id'] ?>"><?=$company['name']?></option>
        <? endforeach; ?>
      </select>
    </div>
    <div class="contbody_forms">
      <b><?=lang('name')?></b><br/>
      <span class="under"><?=lang('require')?></span><br/>
      <input type="text" name="name" value="<?= $name ?>" />
    </div>
    <div class="contbody_forms">
        <b><?=lang('phone')?></b><br/>
        <span class="under"><?=lang('require')?></span><br/>
        <input type="text" name="phone" value="<?= $phone ?>" />
    </div>  
  <div class="contbody_forms">
    <b><?=lang('email')?></b><br/>
    <span class="under"><?=lang('require')?></span><br/>
    <input type="text" name="email" value="<?= $email ?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('city')?></b><br/>
    <span class="under"><?=lang('require')?></span><span></span><br/>
    <input type="text" name="city" value="<?= $city ?>" />
  </div>
  <div class="contbody_forms">
    <b><?=lang('inn')?></b><br/>
    <input type="text" name="inn" value="<?= $inn ?>" />
  </div>
   <input type="hidden" name="lotid" value="<?= $lotid ?>"/>
  <div class="contfin_forms">
    <input type="submit" value="<?=lang('send')?>" />
  </div>
</form>
</td>
</tr>
</table>
