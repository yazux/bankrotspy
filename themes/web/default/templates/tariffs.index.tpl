<table>
    <tr>
        <td valign="top">

            <div class="content">
                <div class="conthead">
                    <h2><i class="icon-key"></i>Тарифы</h2>
                </div>
                <div class="contbody_forms">
                   <?=$text?>
                </div>

            </div>
            <?if($rmenu):?>
            <?foreach($rmenu as $rmenu): ?>
            <div class="tarbody">
                <table >
                    <tr>
                        <td valign="top" width="100%">
                            <h2 class="tarhead"><i class="icon-briefcase"></i> <?=$rmenu['name']?></h2>
                            <span class="costpt">Стоимость подписки: <?=$rmenu['price_source']?> руб.</span>
                            <span class="undertartext"><?=$rmenu['subtext']?></span>
                        </td>
                        <td valign="top"><img height="40px" src="<?=$themepath?>/images/ym.png"/></td>

                    </tr>
                </table>
                <div class="button_div">
                    <hr style="margin-bottom: 13px" />
                    <?if(core::$user_id && (core::$user_id == 0 || core::$user_id == 100)):?>
                    <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
                        <input type="hidden" name="receiver" value="410011048401080">
                        <input type="hidden" name="formcomment" value="<?=$rmenu['name']?> bankrot-spy.ru">
                        <input type="hidden" name="short-dest" value="<?=$rmenu['name']?> bankrot-spy.ru">
                        <input type="hidden" name="label" value="<?=$rmenu['order']?>">
                        <input type="hidden" name="quickpay-form" value="shop">
                        <input type="hidden" name="targets" value="<?=$rmenu['name']?>">
                        <input type="hidden" name="sum2" value="<?=$rmenu['price_source']?>" data-type="number">
                        <input type="hidden" name="sum" value="<?=$rmenu['price']?>" data-type="number">

                        <input type="hidden" name="need-fio" value="true">
                        <input type="hidden" name="need-email" value="true">
                        <input type="hidden" name="need-phone" value="false">
                        <input type="hidden" name="need-address" value="false">
                        <label><input type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label>
                        <label><input type="radio" name="paymentType" value="AC" checked>Банковской картой</label>
                        <input type="submit" value="Оплатить">
                    </form>

                    <?elseif(!core::$user_id):?>
                    <a style="margin-left: 0px;margin-top:7px;padding-left: 15px;padding-right: 15px;" class="urlbutton_index" href="<?=$home?>/user/register" >Зарегистрироваться и оплатить</a>
                    <?else:?>
                    <span style="color: #a6a4a4;display: block;margin-left: 7px;margin-bottom: 10px;">У вас уже есть подписка.</span>
                    <?endif?>
                </div>
            </div>
            <?endforeach?>

            <?else:?>
                <!--<div class="content">
                    <div class="contbody_forms">Нет ни одного пункта меню.</div>
                </div>-->
            <?endif?>


        </td>
        <td class="right_back_menu">
            <div class="right_panel_conf">
                <div class="menu_rt">Меню:</div>
                <div class="elmenu"><a href="<?=$home?>">На главную</a></div>
                <div class="down_rmenu"> </div>
            </div>
        </td>
    </tr>
</table>
<style>
label {margin-right:10px;}
label input {
    margin-right:5px;
}
</style>
<script>
$(function(){
    $('body').on('change', 'input[name=paymentType]', function(){
        var type = $(this).val();
        var form = $(this).parent().parent();
        var sum = $(form).find('input[name="sum2"]').val();

        if (type == 'PC') {
            sum = sum * '1.005';
            sum = sum.toPrecision(4);
            $(form).find('input[name=sum]').val(sum);
        }
        
        if (type == 'AC') {
            sum = sum * '1.020';
            sum = sum.toPrecision(4);
            $(form).find('input[name=sum]').val(sum);
        }
    });
});
</script>