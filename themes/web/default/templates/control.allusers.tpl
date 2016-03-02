<table>
    <tr>
        <td valign="top">
            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-user-male"></i>
                        <?
                            switch( $currentRights ) {
                                case -1: echo "Отключенные пользователи";break;
                                case 0: echo "Пользователи без подписки";break;
                                case 10: echo "Пользователи с подпиской";break;
                                case 11: echo "Пользователи с VIP-подпиской";break;
                                case 20: echo "Партнеры";break;
                                case 70: echo "Техподдержка";break;
                                case 90: echo "Редакторы";break;
                                case 100: echo "Администраторы";break;
                                default: echo "Все категории пользователей";break;
                            }
                        ?>
                    </h2>
                </div>
                    
                <div class="contbody_forms">
                    <form style="display:block;margin-bottom:20px;" method="get">
                        <input type="hidden" name="rights" value="<?=$currentRights?>">
                        <input type="text" placeholder="логин или e-mail" name="search" value="<?=$search?>" style="height: 15px;">
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
                                    Статус: <span class="status"><?=$out['rightsName']?></span><br/>
                                    <?=lang('now')?> <?if($out['online']):?><span class="us_on"> <?=lang('lang_on_anc')?></span><?else:?><span class="us_off"> <?=lang('lang_off_anc')?></span><?endif?>
                                </div>
                                <a href="/user/editprofile?id=<?=$out['id']?>">Редактировать</a>
                            </td>
                            <? if(!empty($out['tariffs'])): ?>
                            <td valign="top">
                                <form id="tariffForm">
                                    <input type="hidden" name="user_id_<?= $out['id'] ?>" value="<?= $out['id'] ?>">
                                    <select name="tariff_id_<?= $out['id'] ?>" id="tariff_id_<?= $out['id'] ?>" style="width: 300px;" title="Выставляет подписку согласно тарифа.">
                                        <? foreach($out['tariffs'] as $tariff): ?>
                                            <option value="<?= $tariff['id'] ?>"><?= $tariff['name'] ?></option>
                                        <? endforeach; ?>
                                        <option value="-1">Подписки отключены</option>
                                    </select>
                                    <br />
                                    <input type="text" name="pay_<?= $out['id'] ?>" value="" style="width: 60px; height: 15px;" title="Сумма оплаты, которую можно установить вне зависимости от цены пакета."> &nbsp;&nbsp;Сумма оплаты
                                    <input type="checkbox" name="message_<?= $out['id'] ?>" value="1" style="margin-left: 10px;" title="Уведомить пользователя об установке тарифа."> Уведомить
                            </td>
                            <td style="vertical-align: top;">
                                    <input type="button" title="Устанавливает клиенту доступ к материалам согласно выставленному тарифу и создает транзакцию про оплату" value="Назначить" onclick="activate(<?= $out['id'] ?>, 0)" style="width: 150px;" /><br />
                                    <input type="button" title="Устанавливает пользователю доступ согласно выставленному тарифу без создания транзакции про оплату." value="Как своему" onclick="activate(<?= $out['id'] ?>, 1)" style="width: 150px;"/>
                                </form>
                            </td>
                            <td style="vertical-align: top;">
                                <form id="rightsForm_<?= $out['id'] ?>">
                                    <input type="hidden" name="user_id_<?= $out['id'] ?>" value="<?= $out['id'] ?>">
                                    <select name="right_id_<?= $out['id'] ?>" id="right_id_<?= $out['id'] ?>" style="width: 150px; margin-top: 3px;" onchange="changeStatus(<?= $out['id'] ?>)" title="Выставляет пользователю права. Можно заблокировать.">
                                        <? foreach($rights as $key => $right): ?>
                                            <option value="<?= $key ?>" <?if ($key==$out['rights']):?>selected<?endif?>><?= $right ?></option>
                                        <? endforeach; ?>
                                        <option value="-1" <?if ($out['rights']==-1):?>selected<?endif?>>Пользователь отключен</option>
                                    </select>
                                    <!--input type="submit" value="Назначить"/-->
                                    <br />
                                </form>
                            </td>
                            <? endif; ?>
                        </tr>
                    </table>
                    </div>
                    <?endforeach?>
                <?else:?>
                    <div class="contbody_forms">Пользователи не найдены.</div>
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
function changeStatus( id ){
    //var userId = $("input[name*='user_id_"+id+"']").val();
    var rightId = $("select[name*='right_id_"+id+"']").val();
    //alert(rightId);
    $.ajax({
        type: 'GET',
        url: '/user/chstatus',
        data: {'user_id':id, 'right_id':rightId},
        success: function(result){
            //alert(result);
            create_notify(result);
        }
    });
    return false;
}

function activate( id, forFriend ){

    var tariffId = $("select[name*='tariff_id_"+id+"']").val();
    var pay = $("input[name*='pay_"+id+"']").val();
    var message = ($("input[name*='message_"+id+"']").prop('checked')?1:0);
    
    var data = {
        'user_id' : id, 
        'tariff_id' : tariffId,
        'pay' : pay,
        'message' : message
    }
    
    if ( forFriend == 1 ) {
        data['forFriend'] = 1;
    } else {
        forFriend = 0;
        data['forFriend'] = 0;
    }
    //alert(data['pay'] + " " + data['message'] + " " + data['forFriend']);
    
    $.ajax({
        type: 'GET',
        url: '/payment/createajax',
        data: data,
        success: function(result){
            //alert($("#tariff_id_"+id).val());
            create_notify(result);
            $("input[name*='pay_"+id+"']").val("");
            $("input[name*='message_"+id+"']").attr('checked', false);
            if (($("#tariff_id_"+id).val() == 4) || ($("#tariff_id_"+id).val() == 7)) {
                $("#right_id_"+id+" [value='11']").prop("selected", "selected");
            } else {
                $("#right_id_"+id+" [value='10']").prop("selected", "selected");
            }
        }
    });
    return false;
}
</script>