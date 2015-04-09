<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b>Ошибка оплаты!</b><br/>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <b>Заказ №:</b>
        <?=$orderid?><br/><br/>

        <b>Детали ошибки:</b><br/>
        - <?=$error_code?> <?if($error_description):?>/ <?=$error_description?><?endif?>
        <br/><br/>

        <b>Что делать?</b><br/>
        - Если списание средств не произошло, повторите попытку.<br/>
        - Если списание средств произошло или ошибка повторяется вновь, скопируйте номер заказа и детали ошибки, создайте тикет в <a href="<?=$home?>/support">техподдержке</a> c подробным описанием ваших действий.
    </div>
    <div class="contfintext">
        <center><a class="urlbutton" href="<?=core::$home?>">На главную страницу</a></center>
    </div>
</div>