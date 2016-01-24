<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead"><h2><i class="icon-user-male"></i> <?=lang('u_online')?></h2></div>
                    
                <div class="contbody_forms">
                    <form style="display:block;margin-bottom:20px;">
                        <input type="text" placeholder="id, логин или e-mail" name="search">
                        <input class="urlbutton_index button_no_top_index" type="submit" value="искать">
                    </form>
               </div> 
                <?if($out):?>
                <?foreach($out as $out): ?>
                <div class="contbody_forms">
                <table>
                    <tr>
                        <td style="width: 60px;">
                            <?if(core::$user_id):?>
                            <a href="<?=$home?>/user/profile?id=<?=$out['id']?>"><img class="avatar" src="<?=$out['avatar']?>"/></a>
                            <?else:?>
                            <img class="avatar" src="<?=$out['avatar']?>"/>
                            <?endif?>
                        </td>
                        <td>
                            <div class="ank">
                                <?if(core::$user_id):?>
                                <a href="<?=$home?>/user/profile?id=<?=$out['id']?>"><b><?=$out['login']?></b></a>
                                <?else:?>
                                <b><?=$out['login']?></b>
                                <?endif?>
                                <br/>
                                Дата регистрации: <?= $out['registered'] ?>
                                <br/>
                                Статус: <span class="status"><?=$out['rights']?></span><br/>
                                <?=lang('now')?> <?if($out['online']):?><span class="us_on"> <?=lang('lang_on_anc')?></span><?else:?><span class="us_off"> <?=lang('lang_off_anc')?></span><?endif?>
                            </div>
                            <a href="/user/editprofile?id=<?=$out['id']?>">Редактировать</a>
                        </td>
                        <? if(!empty($out['tariffs'])): ?>
                        <td valign="top">
                            <form id="tariff" method="POST">
                                <? temp::formid() /* ЭТА ФУНКЦИЯ ОБЯЗАТЕЛЬНА ДЛЯ ВСЕХ ФОРМ!!! */?>
                                <input type="hidden" name="user_id" value="<?= $out['id'] ?>">
                                <select name="tariff_id">
                                    <? foreach($out['tariffs'] as $tariff): ?>
                                    <option value="<?= $tariff['id'] ?>"><?= $tariff['name'] ?></option>
                                    <? endforeach; ?>
                                </select>
                                <input type="submit" value="Активировать"/>
                            </form>
                        </td>
                        <? endif; ?>
                    </tr>
                </table>
                </div>
                <?endforeach?>
                <?else:?>
                <div class="contbody_forms"><?=lang('no_users')?></div>
                <?endif?>
                <div class="contfin_forms" style="padding: 9px 20px">
                    Всего: <?=$total_in?>
                </div>
            </div>

            <?if($navigation):?><div class="navig"><?=$navigation?></div><?endif?>
        </td>
        <td class="right_back_menu">
                <? temp::include('users.online.right.tpl') ?>
        </td>
    </tr>
</table>
<script>
$(function(){
    $('#tariff').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: '/payment/create',
            data: $(this).serialize(),
            success: function(result){
                window.location.reload();
            }
        });
    });
});
</script>