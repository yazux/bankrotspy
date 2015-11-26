<script>
    var sec=20;
    var myVar = setInterval(function(){myTimer()},1000);
    function myTimer()
    {
        sec=sec-1;
        if (sec>=0) document.getElementById("tmr").innerHTML=sec;
        if (sec<0) {window.location.href='<?=core::$home?>'; }

    }
</script>

<div class="content" style="width: 70%; margin: 0 auto; margin-top: 30px;">
    <div class="conthead">
        <table>
            <tr>
                <td>
                    <b>Оплата доступа</b><br/>
                </td>
            </tr>
        </table>
    </div>
    <div class="contbodytext">
        <center>
            Поздравляем! Вы успешно оплатили доступ к информационным материалам сайта.
            <br/>
            <span style="color:#aba9a7;">
                Вы будете автоматически направленны на главную страницу сайта через <b id="tmr"> 20 </b> секунд
            </span>
        </center>
    </div>
    <div class="contfintext">
        <center><a class="urlbutton" href="<?=core::$home?>">Перейти сейчас</a></center>
    </div>
</div>